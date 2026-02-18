<h1 align="center">E-Learning Kampus</h1>

Aplikasi E-Learning berbasis REST API menggunakan Laravel yang mendukung manajemen course, materi, tugas, dan forum diskusi real-time menggunakan WebSocket.

## 🚀 Fitur Utama

### 👨‍🏫 Lecturer (Dosen)
- CRUD Course (Mata Kuliah)
- Upload & Manajemen Materi
- CRUD Assignment (Tugas)
- Memberikan nilai (grading)
- Mengirim Email Assignment dan nilai ke Student
- Melihat laporan course & assignment
- Membuat forum diskusi

### 👨‍🎓 Student (Mahasiswa)
- Enroll ke Course
- Download materi
- Submit tugas
- Membuat forum diskusi

## 🛠️ Teknologi

- **Framework:** Laravel 10
- **Authentication:** Laravel Sanctum
- **Realtime:** Laravel WebSockets
- **Database:** MySQL
- **Architecture:** REST API

## ⚙️ Instalasi & Setup

1. Clone repository:
   ```bash
   git clone https://github.com/rajahariadi/ProjectTest-GarudaCyber.git
   cd ProjectTest-GarudaCyber
   ```

2. Install dependency:
   ```bash
   composer install
   ```

3. Salin file environment:
    ```bash
    cp .env.example .env
    ```

4. Konfirgurasi file .env:
  - **Database:**
    ```bash
    DB_DATABASE=e-learning
    DB_USERNAME=root
    DB_PASSWORD=
    ```
- **Email (dibawah adalah email dummy untuk kirim email):**
    ```bash
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=silvamozara@gmail.com
    MAIL_PASSWORD=tfohmlszbutkiszi
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=silvamozara@gmail.com
    ```
- **Websocket:**
    ```bash
    BROADCAST_DRIVER=pusher
    
    PUSHER_APP_ID=local-app
    PUSHER_APP_KEY=local-key
    PUSHER_APP_SECRET=local-secret
    PUSHER_HOST=127.0.0.1
    PUSHER_PORT=6001
    PUSHER_SCHEME=http
    PUSHER_APP_CLUSTER=mt1
    ```

5. Jalankan perintah inisialisasi:
   ```bash
   php artisan key:generate
   php artisan storage:link
   php artisan migrate
   php artisan db:seed
   ```

5. Jalankan server lokal:
   ```bash
   php artisan serve
   
   ```
6. Jalankan websocket:
   ```bash
   php artisan websocket:serve
   ```

## 🔌 Testing Postman
Testing API menggunakan aplikasi Postman

📁 Download file collection. Lokasi File :
   ```bash
   ProjectTest-GarudaCyber/E-Learning.collection
   ```
⚙️ Setup postman
   
1. Buka aplikasi Postman
2. Klik Import
3. Pilih file:
   ```
   E-Learning.collection
   ```
4. Jalankan request sesuai kebutuhan

## 🔌 API Endpoint

### 🔐 Authentication
| Method        | Endpoint      | Description   |
| ------------- |:-------------:|:-------------:|
| POST          | /api/register | Register user |
| POST          | /api/login    | Login user    |
| POST          | /api/logout   | Logout        |

### 📚 Courses
| Method        | Endpoint                  | Role      | Description                   |
| ------------- |:-------------------------:|:---------:|:-----------------------------:|
| GET           | /api/courses              | All       | Ambil semua course           |
| POST          | /api/courses/             | Lecturer  | Create course                |
| PUT           | /api/courses/{id}         | Lecturer  | Update course                |
| DELETE        | /api/courses/{id}         | Lecturer  | Delete course                |
| POST          | /api/courses/{id}/enroll  | Student   | Daftar course untuk student  |

### 📄 Materials
| Method        | Endpoint                      | Role      | Description                               |
| ------------- |:-----------------------------:|:---------:|:-----------------------------------------:|
| POST          | /api/materials                | Lecturer  | Upload material                           |
| PUT           | /api/materials/{id}           | Lecturer  | Update material (endpoint tambahan)       |
| DELETE        | /api/materials/{id}           | Lecturer  | Delete material (endpoint tambahan)       |
| GET           | /api/materials/{id}/download  | Student   | Download material                         |

### 📝 Assignments
| Method        | Endpoint                      | Role      | Description                               |
| ------------- |:-----------------------------:|:---------:|:-----------------------------------------:|
| POST          | /api/assignments              | Lecturer  | Create assignment                         |
| PUT           | /api/assignments/{id}         | Lecturer  | Update assignments (endpoint tambahan)    |
| DELETE        | /api/assignments/{id}         | Lecturer  | Delete assignments (endpoint tambahan)    |

### 📝 Submissions
| Method        | Endpoint                      | Role      | Description                               |
| ------------- |:-----------------------------:|:---------:|:-----------------------------------------:|
| POST          | /api/submissions              | Student   | Create submission                         |
| POST          | /api/submissions/{id}/grade   | Lecturer  | Create score buat submission student      |

### 💬 Discussions
| Method        | Endpoint                      | Role      | Description                               |
| ------------- |:-----------------------------:|:---------:|:-----------------------------------------:|
| POST          | /api/discussions              | All       | Create discussion                         |
| PUT           | /api/discussions/{id}         | All       | Update discussion (endpoint tambahan)     |
| DELETE        | /api/discussions/{id}         | All       | Delete discussion (endpoint tambahan)     |

### 💬 Discussions
| Method        | Endpoint                                      | Role      | Description                                 |
| ------------- |:---------------------------------------------:|:---------:|:-------------------------------------------:|
| POST          | /api/discussions/{id}/replies                 | All       | Create reply discussion                     |
| PUT           | /api/discussions/{id}/replies/{replies_id}    | All       | Update reply discussion (endpoint tambahan) |
| DELETE        | /api/discussions/{id}/replies/{replies_id}    | All       | Delete reply discussion (endpoint tambahan) |

### 📊 Reports
| Method        | Endpoint                      | Role      | Description                                       |
| ------------- |:-----------------------------:|:---------:|:-------------------------------------------------:|
| GET           | /api/reports/courses          | All       | Ambil Statistik jumlah mahasiswa per mata kuliah  |
| GET           | /api/reports/assignments      | All       | Ambil Statistik tugas yang sudah/belum dinilai    |
| GET           | /api/reports/students/{id}    | All       | Ambil Statistik tugas dan nilai mahasiswa tertentu|
