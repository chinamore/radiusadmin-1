var data = {
    labels: ["01/01/2017", "02/01/2017", "03/01/2017", "04/01/2017", "05/01/2017"],
    datasets: [
        {
            label: "Total de conexões",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#49739C",
            borderColor: "#49739C",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [65, 59, 80, 81, 56],// 55, 40, 32, 41, 70],
            spanGaps: false,
        },

        {
            label: "Total de usuários",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#D75553",
            borderColor: "#D75553",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [20, 78, 22, 40, 32],//, 41, 70, 56, 55, 40],
            spanGaps: false,
        }

    ]
};
                

var ctx = $("#canvas-chart-connection");

var myLineChart = new Chart(ctx, {
    type: 'line',
        data: data,
        options:{animation:{animateScale:!0},responsive:!0,tooltips:{enabled:!1}, legend:{position: 'bottom'}}
});


var ctx = $("#canvas-chart-connection2");

var myLineChart2 = new Chart(ctx, {
    type: 'line',
        data: data,
        options:{animation:{animateScale:!0},responsive:!0,tooltips:{enabled:!1}, legend:{position: 'bottom'}}
});


        var data = {
            labels: [
                "Red", "Blue"
            ],
            datasets: [{

                data: [300, 500],
            
                backgroundColor: [
                    "#49739C",
                    "#D75553",
                ],
              
                hoverBackgroundColor: [                    
                    "#49739C",
                    "#D75553",
                ],                                                                                                                     
            }]
        };

        var ctx = $("#chart-down-up");
        var myPieChart = new Chart(ctx,{
        
            type: 'pie',
            data: data,
            options:{
                animation:{
                    animateScale:true
                },
                responsive: true,
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false
                }

            }
        });


