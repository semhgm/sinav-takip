from flask import Flask, request, jsonify
from flask_cors import CORS
import threading
import cv2
import time
import requests
import uuid
import os

# ==========================
# API CONFIG
# ==========================
API_EVENT_URL = "http://127.0.0.1:8000/api/proctor/event"
API_SNAPSHOT_URL = "http://127.0.0.1:8000/api/proctor/snapshot"
API_HEARTBEAT_URL = "http://127.0.0.1:8000/api/proctor/heartbeat"

# ==========================
# AGENT STATE
# ==========================
app = Flask(__name__)
CORS(app)

AGENT_RUNNING = False
CURRENT_TOKEN = None
STOP_SIGNAL = False

# ==========================
# FLASK LOCAL SERVER
# ==========================
@app.route("/ping", methods=["GET"])
def ping():
    return jsonify({"ok": True})


@app.route("/start", methods=["POST"])
def start():
    global AGENT_RUNNING, CURRENT_TOKEN, STOP_SIGNAL

    data = request.get_json(silent=True) or {}
    token = data.get("token")

    if not token:
        return jsonify({"ok": False, "error": "token required"}), 400

    CURRENT_TOKEN = token
    AGENT_RUNNING = True
    STOP_SIGNAL = False

    print("🎯 Proctoring BAŞLADI | token alındı")
    return jsonify({"ok": True})


@app.route("/stop", methods=["POST"])
def stop():
    global AGENT_RUNNING, CURRENT_TOKEN, STOP_SIGNAL

    data = request.get_json(silent=True) or {}
    token = data.get("token")

    if token != CURRENT_TOKEN:
        return jsonify({"ok": False, "error": "invalid token"}), 403

    print("🛑 Proctoring DURDURULDU")
    AGENT_RUNNING = False
    STOP_SIGNAL = True
    CURRENT_TOKEN = None

    return jsonify({"ok": True})


def run_server():
    print("🚀 Agent local server başlatıldı (5454)")
    app.run(host="127.0.0.1", port=5454, debug=False)


# 🔥 FLASK SERVER
threading.Thread(target=run_server, daemon=True).start()

# ==========================
# TOKEN BEKLE
# ==========================
print("⏳ Agent çalışıyor, start bekleniyor...")

while not AGENT_RUNNING:
    time.sleep(0.5)

print("✅ Token alındı, kamera izleme başlıyor")

# ==========================
# OPENCV SETUP
# ==========================
cap = cv2.VideoCapture(0)

face_cascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)

CHECK_INTERVAL = 1.0
FACE_MISSING_THRESHOLD = 3.0
MULTI_FACE_THRESHOLD = 2
SNAPSHOT_COOLDOWN = 5

face_missing_since = None
last_snapshot_time = 0
last_multi_face_event = 0

# ==========================
# HEARTBEAT (FAILSAFE)
# ==========================
def heartbeat_loop():
    global AGENT_RUNNING, STOP_SIGNAL

    while True:
        if not AGENT_RUNNING or not CURRENT_TOKEN:
            time.sleep(1)
            continue

        try:
            r = requests.post(API_HEARTBEAT_URL, json={
                "token": CURRENT_TOKEN
            }, timeout=2)

            if r.status_code == 200:
                data = r.json()
                if data.get("active") is False:
                    print("🛑 Backend sınav kapalı → agent durduruluyor")
                    AGENT_RUNNING = False
                    STOP_SIGNAL = True
        except:
            pass

        time.sleep(10)


threading.Thread(target=heartbeat_loop, daemon=True).start()

# ==========================
# SNAPSHOT
# ==========================
def send_snapshot(frame, event_type):
    filename = f"snapshot_{uuid.uuid4().hex}.jpg"
    cv2.imwrite(filename, frame)

    try:
        with open(filename, "rb") as img:
            requests.post(
                API_SNAPSHOT_URL,
                files={"image": img},
                data={
                    "token": CURRENT_TOKEN,
                    "event_type": event_type
                },
                timeout=5
            )
        print(f"📸 Snapshot gönderildi: {event_type}")
    except Exception as e:
        print("❌ Snapshot hatası:", e)
    finally:
        if os.path.exists(filename):
            os.remove(filename)

# ==========================
# MAIN LOOP
# ==========================
print("🎥 Kamera izleniyor")

while True:
    if STOP_SIGNAL:
        print("🧹 Agent temizleniyor ve bekleme moduna geçti")
        break

    if not AGENT_RUNNING:
        time.sleep(0.5)
        continue

    ret, frame = cap.read()
    if not ret:
        time.sleep(0.5)
        continue

    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.3, 5)
    now = time.time()

    # FACE MISSING
    if len(faces) == 0:
        if face_missing_since is None:
            face_missing_since = now

        if now - face_missing_since >= FACE_MISSING_THRESHOLD:
            requests.post(API_EVENT_URL, json={
                "token": CURRENT_TOKEN,
                "source": "camera",
                "event_type": "face_missing",
                "level": "warning",
                "metrics": {"faces": 0}
            })

            if now - last_snapshot_time > SNAPSHOT_COOLDOWN:
                send_snapshot(frame, "face_missing")
                last_snapshot_time = now

            face_missing_since = now
    else:
        face_missing_since = None

    # MULTIPLE FACES
    if len(faces) >= MULTI_FACE_THRESHOLD:
        if now - last_multi_face_event > SNAPSHOT_COOLDOWN:
            requests.post(API_EVENT_URL, json={
                "token": CURRENT_TOKEN,
                "source": "camera",
                "event_type": "multiple_faces",
                "level": "critical",
                "metrics": {"faces": len(faces)}
            })

            send_snapshot(frame, "multiple_faces")
            last_multi_face_event = now
            last_snapshot_time = now

    time.sleep(CHECK_INTERVAL)

cap.release()
print("❌ Kamera kapatıldı")
