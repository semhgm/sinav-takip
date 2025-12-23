# Proctoring Agent (Python + OpenCV)

Bu agent, sınav sırasında öğrencinin kamerasını OpenCV ile izleyerek
**proctoring event**’lerini Laravel API’ye gönderir ve gerekli durumlarda
**snapshot (kanıt görseli)** yükler.

---

## 🚀 Özellikler (Mevcut)

- `face_missing` → Yüz algılanmadığında event gönderir
- `multiple_faces` → Birden fazla yüz algılanırsa event gönderir
- Snapshot yükleme (`/api/proctor/snapshot`)
- (Opsiyonel) Heartbeat desteği (`/api/proctor/heartbeat`)

---

## 📦 Gereksinimler

- **Python 3.10+** (önerilen: 3.11 / 3.12)
- Web kamera
- İnternet / API erişimi
- Laravel backend çalışıyor olmalı  
  (`php artisan serve` veya nginx/apache)

> ⚠️ Not: Tarayıcı (Chrome vb.) kamerayı kullanıyorsa agent kamera açamayabilir.  
> Test sırasında kamerayı **tek uygulama** kullansın.

---

## 🔧 Kurulum

### 1️⃣ Agent klasörüne gir

```bash
cd tools/proctor_agent

2️⃣ Sanal ortam oluştur (önerilir)
Windows (PowerShell)
python -m venv .venv
.\.venv\Scripts\Activate.ps1

macOS / Linux
python3 -m venv .venv
source .venv/bin/activate

3️⃣ Bağımlılıkları yükle
pip install -r requirements.txt


requirements.txt içeriği:

opencv-python
requests

🧩 Laravel Tarafı Hazırlık
1️⃣ Sınav oturumunu başlat

Öğrenci sınava girince exam_sessions tablosunda bir kayıt oluşur.

2️⃣ proctor_token değerini al
SELECT id, proctor_token, status
FROM exam_sessions
ORDER BY id DESC
LIMIT 1;


Bu proctor_token, agent’ın API’ye kimlikli istek atmasını sağlar.

⚙️ Agent Konfigürasyonu

agent.py içinde aşağıdaki alanları düzenle:

API_URL = "http://127.0.0.1:8000/api/proctor/event"
SNAPSHOT_URL = "http://127.0.0.1:8000/api/proctor/snapshot"
HEARTBEAT_URL = "http://127.0.0.1:8000/api/proctor/heartbeat"

TOKEN = "BURAYA_DB_PROCTOR_TOKEN"

Aynı bilgisayarda çalışıyorsan
http://127.0.0.1:8000

Farklı bilgisayarda çalışıyorsan
http://192.168.x.x:8000


⚠️ Windows Firewall kullanıyorsan Laravel çalıştığı makinede 8000 portuna izin ver.

▶️ Agent’ı Çalıştır
python agent.py


Başarılı çalışınca terminalde şunu görürsün:

✅ Proctor Agent başladı

🧪 Test Senaryosu

Kameraya bak → event oluşmamalı

Yüzü kadrajdan çıkar → 3–4 sn sonra face_missing

(Varsa) 2 kişi kadraja girsin → multiple_faces + snapshot

✅ Backend Doğrulama
1️⃣ Postman ile event testi
POST /api/proctor/event

{
  "token": "PROCTOR_TOKEN",
  "source": "camera",
  "event_type": "face_missing",
  "level": "warning",
  "metrics": {
    "faces": 0,
    "duration": 3.5
  }
}


Beklenen cevap:

{ "ok": true }

2️⃣ MongoDB kontrolü

MongoDB Compass → proctoring DB → ilgili collection
Event kayıtlarını görebilirsin.

3️⃣ Snapshot kontrolü (Laravel storage)

Snapshot’lar burada oluşur:

storage/app/public/proctor_snapshots/


Public erişim için:

php artisan storage:link


Tarayıcıdan:

http://127.0.0.1:8000/storage/proctor_snapshots/<dosya>.jpg

🛠️ Sık Karşılaşılan Sorunlar
Kamera açılamıyor

Chrome / Teams / Zoom kamerayı kullanıyor olabilir

Tek uygulama kamera kullansın

API timeout / connection refused

Laravel çalışıyor mu?

IP doğru mu?

Firewall izinleri var mı?

422 Unprocessable Content

Eksik alanlar olabilir:

token

source

event_type

level

🗺️ Yol Haritası (Planlanan)

Agent auto-start & browser handshake

Browser event’leri (tab değişimi, focus kaybı)

Mikrofon analizi

Integrity score + admin timeline

ℹ️ Not

Bu agent:

Laravel runtime’ını etkilemez

Composer / PHP autoload ile ilişkili değildir

Sadece API client olarak çalışır


---



```bash

