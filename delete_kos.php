<?php
require 'functions.php';

if (deleteKos($_GET['id']) > 0) {
    echo "
        <script>
            document.location.href = 'index.php'
        </script>
		";
} else {
    echo "
        <script>
            alert('Hapus Kos Gagal !!!');
            document.location.href = 'index.php'
        </script>
		";
}
