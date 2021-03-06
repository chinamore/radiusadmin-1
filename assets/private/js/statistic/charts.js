
function createSummaryConnectionsChart( dates, connections, users ) {

    var ctx = $("#canvas-chart-connection");

    var chartSummaryConnections = new Chart(ctx, {
        type: "line",
        data: {
            labels: dates,
                datasets: [
                    {
                        label: connections.label,
                        fill: false,
                        backgroundColor: "#49739C",
                        borderColor: "#49739C",
                        data: connections.data,
                        spanGaps: false,
                    },
                    {
                        label: users.label,
                        fill: false,
                        backgroundColor: "#D75553",
                        borderColor: "#D75553",
                        data: users.data,
                        spanGaps: false,
                    }
                ]
        },
        options: {
            animation: { 
                animateScale: true
            },
            
            responsive: true,
            
            tooltips: {
                enabled: false
            }, 
            
            legend:{
                position: "bottom"
            }
        }
    });
}


function createAvgChart( down, up ) {

    var ctx = $("#chart-down-up");

    var myPieChart = new Chart( ctx, {
        
        type: "pie",
        
        data: {
        
            datasets: [
                {
                    data: [down, up],
            
                    backgroundColor: [
                        "#49739C",
                        "#D75553",
                    ],
              
                    hoverBackgroundColor: [                    
                        "#49739C",
                        "#D75553",
                    ],
                }
            ]
        },
      
        options: {
            
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
}
