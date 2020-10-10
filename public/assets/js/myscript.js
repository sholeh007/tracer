// toaster
const flash = $('.flash').data('flashdata');
if (flash) {
    toastr.options = {
        "closeButton": true,
    }
    toastr["success"](flash, " Success");
}

// datatables
$(document).ready(function () {
    $('#myTable').DataTable({
        "pagingType": "first_last_numbers",
        "responsive": true,
        "scroller": true,
        "scrollY": 300,
        // "columnDefs": [{
        //     "orderable": false,
        //     "targets": [1, 2, 3, 4]
        // }],
        "lengthChange": false
    });
});

// preview gambar
function previewImg() {
    const gambar = document.querySelector('#gambar');
    const gambarLabel = document.querySelector('.custom-file-label');
    const imgPreview = document.querySelector('.img-preview');

    gambarLabel.textContent = gambar.files[0].name;

    const fileGambar = new FileReader();
    fileGambar.readAsDataURL(gambar.files[0]);

    fileGambar.onload = function (e) {
        imgPreview.src = e.target.result;
    }
}

// disabled form kuesioner
let belum = document.querySelector('#belum');
let sudah = document.querySelector('#sudah');
let instansi = document.getElementById('disable');
let radio1 = document.getElementsByName('menunggu');
let radio2 = document.getElementsByName('gaji');
let radio3 = document.getElementsByName('materi');
let radio4 = document.getElementsByName('pendukung');
let radio5 = document.getElementsByName('hambatan');
let radio6 = document.getElementsByName('skill');

belum.addEventListener('change', function () {
    if (belum.checked) {
        instansi.disabled = true;
        for (let i = 0; i < radio1.length; i++) {
            radio1[i].disabled = true;
        }
        for (let i = 0; i < radio2.length; i++) {
            radio2[i].disabled = true;
        }
        for (let i = 0; i < radio3.length; i++) {
            radio3[i].disabled = true;
        }
        for (let i = 0; i < radio4.length; i++) {
            radio4[i].disabled = true;
        }
        for (let i = 0; i < radio5.length; i++) {
            radio5[i].disabled = true;
        }
        for (let i = 0; i < radio6.length; i++) {
            radio6[i].disabled = true;
        }
    }
});

document.onreadystatechange = function belum_cek() {
    if (belum.checked) {
        instansi.disabled = true;
        for (let i = 0; i < radio1.length; i++) {
            radio1[i].disabled = true;
        }
        for (let i = 0; i < radio2.length; i++) {
            radio2[i].disabled = true;
        }
        for (let i = 0; i < radio3.length; i++) {
            radio3[i].disabled = true;
        }
        for (let i = 0; i < radio4.length; i++) {
            radio4[i].disabled = true;
        }
        for (let i = 0; i < radio5.length; i++) {
            radio5[i].disabled = true;
        }
        for (let i = 0; i < radio6.length; i++) {
            radio6[i].disabled = true;
        }
    }
}

sudah.addEventListener('change', function () {
    if (sudah.checked) {
        instansi.disabled = false;
        for (let i = 0; i < radio1.length; i++) {
            radio1[i].disabled = false;
        }
        for (let i = 0; i < radio2.length; i++) {
            radio2[i].disabled = false;
        }
        for (let i = 0; i < radio3.length; i++) {
            radio3[i].disabled = false;
        }
        for (let i = 0; i < radio4.length; i++) {
            radio4[i].disabled = false;
        }
        for (let i = 0; i < radio5.length; i++) {
            radio5[i].disabled = false;
        }
        for (let i = 0; i < radio6.length; i++) {
            radio6[i].disabled = false;
        }
    }
});