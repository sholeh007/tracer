let total = ['total'].concat(kejuruan);
let jumlahJurusan = [].concat(alumni, dataJurusan);

// function tahun
function createYear() {
  let year = new Date();
  let years = year.getFullYear();
  let tahun = [];
  for (let i = 2011; i <= years; i++) {
    tahun.push(i);
  }
  return tahun;
}
// akhir function tahun

// function backgroundColor
function getColor() {
  let warna = [];
  for (let i = 1; i <= jumlahJurusan.length; i++) {
    for (let a = 1; a <= createYear().length; a++) {
      warna.push('rgba(255, 99, 132, 0.2)', 'rgba(245, 230, 83, 1)', 'rgba(78, 205, 196, 1)');
    }
  }
  return warna;
}
// akhir function backgroundColor

// function backgroundColor jurusan gender
function getWarnaPria() {
  let warna = [];
  for (let i = 1; i <= kejuruan.length; i++) {
    warna.push('rgba(255, 99, 132, 0.2)');
  }
  return warna;
}

function getWarnaWanita() {
  let warna = [];
  for (let i = 1; i <= kejuruan.length; i++) {
    warna.push('rgba(254, 241, 96, 1)');
  }
  return warna;
}
// akhir function backgroundColor jurusan gender

// chart lulusan
let ctx = document.getElementById('lulusan').getContext("2d");
let chart1 = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: total,
    datasets: [{
      label: (''),
      data: jumlahJurusan,
      backgroundColor: getColor(),
      borderWidth: 2
    }]
  },
  options: {
    legend: {
      display: false
    },
    tooltips: {
      enabled: false
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
});
// function update chart lulusan
function chartBarUpdate() {
  let val = document.getElementById('lulus').value;

  switch (val) {
    default:
      chart1.data.labels = total;
      chart1.data.datasets[0].data = jumlahJurusan;
      chart1.update();
      break;
    case 'tahun':
      chart1.data.labels = createYear();
      chart1.data.datasets[0].data = angkatan;
      chart1.update();
      break;
    case 'jurusan':
      chart1.data.labels = kejuruan;
      chart1.data.datasets[0].data = dataJurusan;
      chart1.update();
  }
}
// end chart lulusan

// chart gender 
let ctx2 = document.getElementById('gender').getContext('2d');
let ctx22 = document.getElementById('gender_tahun');
let ctx23 = document.getElementById('gender_jurusan').getContext('2d');

document.querySelector('#gender_tahun').style.display = 'none';
document.querySelector('#gender_jurusan').style.display = 'none';
let chart2 = new Chart(ctx2, {
  type: 'pie',
  data: {
    labels: [`pria : ${jmlh_pria}`, `wanita : ${jmlh_wanita}`],
    datasets: [{
      data: [
        Number(pria).toFixed(2),
        Number(wanita).toFixed(2)
      ],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)'
      ],
      borderWidth: 2
    }]
  },
  options: {
    tooltips: {
      enabled: false
    },
    plugins: {
      datalabels: {
        formatter: (value) => {
          return value + ' %';
        }
      }
    }
  }
});

function chartPieUpdate() {
  let val = document.getElementById('grafik_gender').value;

  switch (val) {
    default:
      document.querySelector('#gender').style.display = 'block';
      document.querySelector('#gender_tahun').style.display = 'none';
      document.querySelector('#gender_jurusan').style.display = 'none';

      chart2.update();
      break;

    case 'tahun_gender':
      document.querySelector('#gender').style.display = 'none';
      document.querySelector('#gender_tahun').style.display = 'block';
      document.querySelector('#gender_jurusan').style.display = 'none';
      chart2 = new Chart(ctx22, {
        type: 'line',
        data: {
          labels: createYear(),
          datasets: [{
            label: ('pria'),
            data: angkatanPria,
            borderColor: ('rgba(25, 181, 254, 1)'),
            fill: false,
            borderWidth: 3
          }, {
            label: ('wanita'),
            data: angkatanWanita,
            borderColor: ('rgba(247, 202, 24, 1)'),
            fill: false, //ini untuk hilangkan fill warna
            borderWidth: 3
          }]
        },
        options: {
          // scales: {
          //   yAxes: [{
          //     ticks: {
          //       beginAtZero: true,
          //       min: 0,
          //       max: 100,
          //       stepSize: 20
          //     }
          //   }]
          // },
          tooltips: {
            enabled: true
          }
        },
      });
      chart2.update();
      break;

    case 'jurusan_gender':
      document.querySelector('#gender').style.display = 'none';
      document.querySelector('#gender_tahun').style.display = 'none';
      document.querySelector('#gender_jurusan').style.display = 'block';
      chart2 = new Chart(ctx23, {
        type: 'bar',
        data: {
          labels: kejuruan,
          datasets: [{
            label: ('pria'),
            data: jurusanPria,
            backgroundColor: getWarnaPria(),
            borderWidth: 2
          }, {
            label: ('wanita'),
            data: jurusanWanita,
            backgroundColor: getWarnaWanita(),
            borderWidth: 2
          }]
        },
        options: {
          tooltips: {
            enabled: false
          }
        }
      });
      chart2.update();
  }
}
// end chart gender

// chart status bekerja
let ctx4 = document.getElementById('status_kerja').getContext('2d');
let ctx41 = document.getElementById('tahun_status_kerja').getContext('2d');
let ctx42 = document.getElementById('jurusan_status_kerja').getContext('2d');

document.querySelector('#tahun_status_kerja').style.display = 'none';
document.querySelector('#jurusan_status_kerja').style.display = 'none';
let chart4 = new Chart(ctx4, {
  type: 'pie',
  data: {
    labels: [`sudah bekerja : ${sudah_bekerja}`,
    `belum bekerja : ${belum_bekerja}`
    ],
    datasets: [{
      data: [
        Number(persen_sudah_bekerja).toFixed(2),
        Number(persen_belum_bekerja).toFixed(2)
      ],
      backgroundColor: [
        'rgba(241, 90, 34, 1)',
        'rgba(247, 202, 24, 1)'
      ],
      borderWidth: 2
    }]
  },
  options: {
    plugins: {
      datalabels: {
        color: '#fff',
        formatter: (value) => {
          return value + ' %';
        }
      }
    },
    tooltips: {
      enabled: false
    }
  }
});

function chartBekerjaUpdate() {
  let val = document.getElementById('grafik_bekerja').value;

  switch (val) {
    default:
      document.querySelector('#status_kerja').style.display = 'block';
      document.querySelector('#tahun_status_kerja').style.display = 'none';
      document.querySelector('#jurusan_status_kerja').style.display = 'none';

      chart4.update();
      break;

    case 'tahun_bekerja':
      document.querySelector('#status_kerja').style.display = 'none';
      document.querySelector('#tahun_status_kerja').style.display = 'block';
      document.querySelector('#jurusan_status_kerja').style.display = 'none';
      chart4 = new Chart(ctx41, {
        type: 'line',
        data: {
          labels: createYear(),
          datasets: [{
            label: ('sudah'),
            data: tahunSudahBekerja,
            borderColor: ('rgba(25, 181, 254, 1)'),
            fill: false,
            borderWidth: 3
          }, {
            label: ('belum'),
            data: tahunBelumBekerja,
            borderColor: ('rgba(247, 202, 24, 1)'),
            fill: false, //ini untuk hilangkan fill warna
            borderWidth: 3
          }]
        },
        options: {
          tooltips: {
            enabled: true
          }
        },
      });
      chart4.update();
      break;
    case 'jurusan_bekerja':
      document.querySelector('#status_kerja').style.display = 'none';
      document.querySelector('#tahun_status_kerja').style.display = 'none';
      document.querySelector('#jurusan_status_kerja').style.display = 'block';
      chart4 = new Chart(ctx42, {
        type: 'bar',
        data: {
          labels: kejuruan,
          datasets: [{
            label: ('sudah'),
            data: jurusanSudahBekerja,
            backgroundColor: getWarnaPria(),
            borderWidth: 2
          }, {
            label: ('belum'),
            data: jurusanBelumBekerja,
            backgroundColor: getWarnaWanita(),
            borderWidth: 2
          }]
        },
        options: {
          tooltips: {
            enabled: false
          }
        }
      });
      chart4.update();
  }
}
// akhir chart status bekerja

// chart penggunaan pengetahuan
let ctx5 = document.getElementById('penggunaan_pengetahuan').getContext('2d');
let chart5 = new Chart(ctx5, {
  type: 'pie',
  data: {
    labels: [`sudah sesuai`,
      `belum sesuai`
    ],
    datasets: [{
      data: [
        Number(persenPenggunaanPengetahuan).toFixed(2),
        Number(persenBelumPenggunaanPengetahuan).toFixed(2)
      ],
      backgroundColor: [
        'rgba(38, 194, 129, 1)',
        'rgba(135, 211, 124, 1)'
      ],
      borderWidth: 2
    }]
  },
  options: {
    plugins: {
      datalabels: {
        color: '#fff',
        formatter: (value) => {
          return value + ' %';
        }
      }
    },
    tooltips: {
      enabled: false
    }
  }
});
// akhir chart status bekerja