// (function ($) {
//     // Salse & Revenue Chart
//     var ctx2 = $("#salse-revenue").get(0).getContext("2d");
//     var myChart2 = new Chart(ctx2, {
//         type: "line",
//         data: {
//             labels: ["2016", "2017", "2018", "2019", "2020", "2021", "2022"],
//             datasets: [
//                 {
//                     label: "Cash",
//                     data: [15, 30, 55, 45, 70, 65, 85],
//                     backgroundColor: "rgba(0, 156, 255, .5)",
//                     fill: true,
//                 },
//                 {
//                     label: "Midtrans",
//                     data: [99, 135, 170, 130, 190, 180, 270],
//                     backgroundColor: "rgba(0, 156, 255, .3)",
//                     fill: true,
//                 },
//             ],
//         },
//         options: {
//             responsive: true,
//         },
//     });
// })(jQuery);

// document.addEventListener("DOMContentLoaded", function () {
//     var ctx = document.getElementById("salse-revenue-modal").getContext("2d");
//     new Chart(ctx, {
//         type: "line",
//         data: {
//             labels: ["2016", "2017", "2018", "2019", "2020", "2021", "2022"],
//             datasets: [
//                 {
//                     label: "Cash",
//                     data: [15, 30, 55, 45, 70, 65, 85],
//                     backgroundColor: "rgba(0, 156, 255, .5)",
//                     fill: true,
//                 },
//                 {
//                     label: "Midtrans",
//                     data: [99, 135, 170, 130, 190, 180, 270],
//                     backgroundColor: "rgba(0, 156, 255, .3)",
//                     fill: true,
//                 },
//             ],
//         },
//         options: {
//             responsive: true,
//             maintainAspectRatio: false
//         },
//     });
// });

// document.addEventListener("DOMContentLoaded", function () {
//     var ctx = document.getElementById("salse-revenue-modal").getContext("2d");
//     new Chart(ctx, {
//         type: "line",
//         data: {
//             labels: Array.from({ length: 31 }, (_, i) => (i + 1).toString()), // ["1", "2", ..., "31"]
//             datasets: [
//                 {
//                     label: "Cash",
//                     data: [
//                         500000, 750000, 2000000, 3500000, 1000000, 4000000,
//                         300000, 2500000, 2200000, 0, 900000, 500000, 1000000,
//                         2000000, 3000000, 4000000, 4500000, 3500000, 200000,
//                         1800000, 2600000, 1500000, 2700000, 2900000, 3100000,
//                         3500000, 3700000, 3900000, 4100000, 4300000, 4500000,
//                     ],
//                     backgroundColor: "rgba(0, 156, 255, .5)",
//                     fill: true,
//                 },
//                 {
//                     label: "Midtrans",
//                     data: [
//                         2500000, 3000000, 3500000, 4000000, 1500000, 1000000,
//                         2000000, 1800000, 1600000, 1400000, 1200000, 1000000,
//                         800000, 600000, 400000, 200000, 100000, 300000, 500000,
//                         700000, 900000, 1100000, 1300000, 1500000, 1700000,
//                         1900000, 2100000, 2300000, 2500000, 2700000, 2900000,
//                     ],
//                     backgroundColor: "rgba(0, 156, 255, .3)",
//                     fill: true,
//                 },
//             ],
//         },
//         options: {
//             responsive: true,
//             maintainAspectRatio: false,
//             scales: {
//                 y: {
//                     min: 0,
//                     max: 5000000,
//                     ticks: {
//                         stepSize: 250000,
//                         callback: function (value) {
//                             return "Rp " + value.toLocaleString("id-ID");
//                         },
//                     },
//                 },
//                 x: {
//                     title: {
//                         display: true,
//                         text: "Tanggal (1 - 31)",
//                     },
//                 },
//             },
//         },
//     });
// });


