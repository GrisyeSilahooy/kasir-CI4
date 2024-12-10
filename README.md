# kasir-ci4
Aplikasi ini merupakan contoh project uji kompetensi program keahlian Rekayasa Perangkat Lunak tahun 2023/2024.

# Download dan Instalasi
1. Jalankan CMD / Terminal
2. Masuk ke drive D: atau yang lain jika di linux silahkan masuk direktori mana saja
3. Jalankan perintah : 
    <code>
    git clone https://github.com/GrisyeSilahooy/kasir-ci4.git
    </code>
4. Lakukan update dengan perintah 
   composer update
5. Ganti file env menjadi .env
6. Seting :
    
    <code>CI_ENVIRONMENT = development atau production</code>
   
    <code>app.baseURL = 'http://localhost:8080'</code>

    
    <code>database.default.hostname = localhost
    database.default.database = kasir
    database.default.username = root
    database.default.password = 
    database.default.DBDriver = MySQLi
    </code>
