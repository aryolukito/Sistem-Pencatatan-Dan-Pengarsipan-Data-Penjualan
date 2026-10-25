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

        /* Sidebar Style */
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
                <img src="../../assets/gambar/logo.png"> <span>Sipencar</span>
            </div>
        </div>
        <a href="admin_dashboard.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-520q-33 0-56.5-23.5T120-600v-160q0-33 23.5-56.5T200-840h160q33 0 56.5 23.5T440-760v160q0 33-23.5 56.5T360-520H200Zm0 400q-33 0-56.5-23.5T120-200v-160q0-33 23.5-56.5T200-440h160q33 0 56.5 23.5T440-360v160q0 33-23.5 56.5T360-120H200Zm400-400q-33 0-56.5-23.5T520-600v-160q0-33 23.5-56.5T600-840h160q33 0 56.5 23.5T840-760v160q0 33-23.5 56.5T760-520H600Zm0 400q-33 0-56.5-23.5T520-200v-160q0-33 23.5-56.5T600-440h160q33 0 56.5 23.5T840-360v160q0 33-23.5 56.5T760-120H600Z"/></svg> <span>Dashboard</span>
        </a>

        <a href="data_penjualan.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M200-120q-33 0-56.5-23.5T120-200v-600q0-17 11.5-28.5T160-840q17 0 28.5 11.5T200-800v600h600q17 0 28.5 11.5T840-160q0 17-11.5 28.5T800-120H200Zm80-120q-17 0-28.5-11.5T240-280v-280q0-17 11.5-28.5T280-600h80q17 0 28.5 11.5T400-560v280q0 17-11.5 28.5T360-240h-80Zm200 0q-17 0-28.5-11.5T440-280v-480q0-17 11.5-28.5T480-800h80q17 0 28.5 11.5T600-760v480q0 17-11.5 28.5T560-240h-80Zm200 0q-17 0-28.5-11.5T640-280v-120q0-17 11.5-28.5T680-440h80q17 0 28.5 11.5T800-400v120q0 17-11.5 28.5T760-240h-80Z"/></svg> <span>Data Penjualan</span>
        </a>

        <a href="data_ternak.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#fff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M8 16h8v2H8zm0-4h8v2H8zm6-10H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg><span>Data Hewan</span>
        </a>

        <a href="data_pengguna.php">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M80-240v-32q0-34 17-62.5t47-43.5q57-29 118.5-46T388-441q14 0 22 12.5t3 26.5q-6 21-9 42t-3 43q0 29 6 56t17 53q8 17-1.5 32.5T396-160H160q-33 0-56.5-23.5T80-240Zm600 0q33 0 56.5-23.5T760-320q0-33-23.5-56.5T680-400q-33 0-56.5 23.5T600-320q0 33 23.5 56.5T680-240ZM400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm234 328-6-28q-12-5-22.5-10.5T584-204l-29 9q-13 4-25.5-1T510-212l-8-14q-7-12-5-26t13-23l22-19q-2-14-2-26t2-26l-22-19q-11-9-13-22.5t5-25.5l9-15q7-11 19-16t25-1l29 9q11-8 21.5-13.5T628-460l6-29q3-14 13.5-22.5T672-520h16q14 0 24.5 9t13.5 23l6 28q12 5 22.5 11t21.5 15l27-9q14-5 27 0t20 17l8 14q7 12 5 26t-13 23l-22 19q2 12 2 25t-2 25l22 19q11 9 13 22.5t-5 25.5l-9 15q-7 11-19 16t-25 1l-29-9q-11 8-21.5 13.5T732-180l-6 29q-3 14-13.5 22.5T688-120h-16q-14 0-24.5-9T634-152Z"/></svg><span>Kelola Pengguna</span>
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