<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        // Fungsi untuk menampilkan konfirmasi logout
        function confirmLogout() {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "../../config/logout.php"; // Redirect ke halaman logout
            }
        }
    </script>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body {
            font-family: "Poppins", serif;
            transition: margin-left 0.3s ease;
        }
        .sidebar {
            height: 100vh;
            background: linear-gradient(135deg, #16a085, #1e8e5f);
            color: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            padding-top: 30px;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
        }

        .sidebar .sidebar-header {
            text-align: left;
            margin-bottom: 30px;
            font-size: 1.4rem;
            font-weight: bold;
            letter-spacing: 1px;
            color: #fff;
            padding: 12px 10px;
        }
        .sidebar .logo img {
            max-width: 20%;
            height: auto;
        }

        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            padding: 12px 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            margin: 5px 0;
            transition: background-color 0.3s ease, transform 0.3s ease-in-out, box-shadow 0.3s ease;
        }

        
        .sidebar a:hover {
            background-color: #1abc9c;
            transform: scale(0.9);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar .menu-icon {
            font-size: 1.5rem;
        }

        .sidebar .dropdown-menu {
            background-color: #1abc9c;
            border: none;
        }

        .sidebar .dropdown-menu a {
            color: #ffffff;
            padding: 10px 25px;
        }

        .sidebar .dropdown-menu a:hover {
            background-color: #16a085;
        }

        .sidebar .logout {
            margin-top: auto;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                box-shadow: none;
            }

            body {
                margin-left: 0;
            }
            .sidebar .sidebar-header {
                text-align: center;
                margin-bottom: 10px;
                font-size: 1.4rem;
            }
            .sidebar .logo img {
                max-width: 10%;
                height: auto;
            }

            .sidebar a {
                justify-content: center;
                padding: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="../../assets/gambar/logo.png">
                <span>Sipencar</span>
            </div>
        </div>
        <a href="petugas_dashboard.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-520q-33 0-56.5-23.5T120-600v-160q0-33 23.5-56.5T200-840h160q33 0 56.5 23.5T440-760v160q0 33-23.5 56.5T360-520H200Zm0 400q-33 0-56.5-23.5T120-200v-160q0-33 23.5-56.5T200-440h160q33 0 56.5 23.5T440-360v160q0 33-23.5 56.5T360-120H200Zm400-400q-33 0-56.5-23.5T520-600v-160q0-33 23.5-56.5T600-840h160q33 0 56.5 23.5T840-760v160q0 33-23.5 56.5T760-520H600Zm0 400q-33 0-56.5-23.5T520-200v-160q0-33 23.5-56.5T600-440h160q33 0 56.5 23.5T840-360v160q0 33-23.5 56.5T760-120H600Z"/></svg> <span>Dashboard</span>
        </a>

        <a href="penjualan_input.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M427-428H221q-22 0-37.5-15.5T168-481q0-22 15.5-37.5T221-534h206v-206q0-22 15.5-37.5T480-793q22 0 37.5 15.5T533-740v206h206q22 0 37.5 15.5T792-481q0 22-15.5 37.5T739-428H533v206q0 22-15.5 37.5T480-169q-22 0-37.5-15.5T427-222v-206Z"/></svg><span>Input Penjualan</span>
        </a>

        <a href="penjualan.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM208-800h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Z"/></svg><span>Penjualan</span>
        </a>

        <a href="#" class="logout" onclick="confirmLogout()">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h240q17 0 28.5 11.5T480-800q0 17-11.5 28.5T440-760H200v560h240q17 0 28.5 11.5T480-160q0 17-11.5 28.5T440-120H200Zm487-320H400q-17 0-28.5-11.5T360-480q0-17 11.5-28.5T400-520h287l-75-75q-11-11-11-27t11-28q11-12 28-12.5t29 11.5l143 143q12 12 12 28t-12 28L669-309q-12 12-28.5 11.5T612-310q-11-12-10.5-28.5T613-366l74-74Z"/></svg><span>Logout</span>
        </a>
    </div>

    <!-- Content area (konten utama) -->
    <div class="content" style="margin-left: 250px; padding: 20px;">
        <!-- Konten lainnya -->
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
