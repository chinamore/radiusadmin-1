

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





