import cv2
import time
import requests
import uuid

API_URL = "http://127.0.0.1:8000/api/proctor/event"
SNAPSHOT_URL = "http://127.0.0.1:8000/api/proctor/snapshot"
TOKEN = "52XB1A3S4oMSwltOrLRK9132kE1M4V9BwhL4yJtv"

CHECK_INTERVAL = 1.0
FACE_MISSING_THRESHOLD = 3.0
MULTI_FACE_THRESHOLD = 2
SNAPSHOT_COOLDOWN = 5  # saniye

cap = cv2.VideoCapture(0)

face_cascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)

face_missing_since = None
last_snapshot_time = 0
last_multi_face_event = 0


def send_snapshot(frame, event_type):
    filename = f"snapshot_{uuid.uuid4().hex}.jpg"
    cv2.imwrite(filename, frame)

    try:
        with open(filename, "rb") as img:
            requests.post(
                SNAPSHOT_URL,
                files={"image": img},
                data={
                    "token": TOKEN,
                    "event_type": event_type
                },
                timeout=5
            )
        print(f"📸 Snapshot gönderildi ({event_type})")
    except Exception as e:
        print("❌ Snapshot gönderilemedi:", e)


print("✅ Proctor Agent başladı")

while True:
    ret, frame = cap.read()
    if not ret:
        break

    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.3, 5)
    now = time.time()

    # ---------------------------
    # 1) FACE MISSING
    # ---------------------------
    if len(faces) == 0:
        if face_missing_since is None:
            face_missing_since = now

        if now - face_missing_since >= FACE_MISSING_THRESHOLD:
            requests.post(API_URL, json={
                "token": TOKEN,
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

    # ---------------------------
    # 2) MULTIPLE FACES (CRITICAL)
    # ---------------------------
    if len(faces) >= MULTI_FACE_THRESHOLD:
        if now - last_multi_face_event > SNAPSHOT_COOLDOWN:
            requests.post(API_URL, json={
                "token": TOKEN,
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
