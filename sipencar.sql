-- Tabel untuk Menyimpan Pengguna (Petugas, Admin)
CREATE TABLE pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    role ENUM('admin', 'petugas') NOT NULL,  -- Admin atau Petugas
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk Menyimpan Jenis Hewan dan Harga
CREATE TABLE jenis_hewan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,  -- Harga per ekor
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk Menyimpan Transaksi Penjualan
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    petugas_id INT NOT NULL,
    jenis_hewan_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) AS (harga * jumlah) STORED,  -- Total harga = harga * jumlah
    status ENUM('draft', 'final') NOT NULL DEFAULT 'draft',  -- Status transaksi
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (petugas_id) REFERENCES pengguna(id),  -- Menghubungkan ke tabel pengguna
    FOREIGN KEY (jenis_hewan_id) REFERENCES jenis_hewan(id)  -- Menghubungkan ke tabel jenis_hewan
);

-- Tabel untuk Menyimpan Riwayat Login
CREATE TABLE login_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengguna_id INT NOT NULL,
    ip_address VARCHAR(255),
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengguna_id) REFERENCES pengguna(id)
);

-- Contoh Data untuk Tabel pengguna
INSERT INTO pengguna (username, password, nama, role) VALUES
('admin', 'admin123', 'Admin Utama', 'admin'),
('petugas01', 'petugas123', 'Petugas A', 'petugas'),
('petugas02', 'petugas123', 'Petugas B', 'petugas');

-- Contoh Data untuk Tabel jenis_hewan
INSERT INTO jenis_hewan (nama, harga) VALUES
('Kambing', 1500000),
('Sapi', 5000000),
('Kuda', 7000000);

-- Contoh Data untuk Tabel transaksi (Draft dan Final)
INSERT INTO transaksi (petugas_id, jenis_hewan_id, jumlah, harga, status) VALUES
(2, 1, 5, 1500000, 'draft'),
(2, 2, 3, 5000000, 'draft'),
(3, 3, 2, 7000000, 'final');
