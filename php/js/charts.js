// Create the chart for the trend analysis
function createLineChart(deviation) {
    var goal = {
        x: [],
        y: [],
        mode: 'lines',
        name: 'goal',
        line: {
            color: '#3498db'
        }
    };

    var positiv = {
        x: [],
        y: [],
        mode: 'markers',
        name: 'positiv',
        marker: {
            color: '#28a745',
            size: 10
        }
    };

    var neutral = {
        x: [],
        y: [],
        mode: 'markers',
        name: 'neutral',
        marker: {
            color: '#f39c12',
            size: 10
        }
    };

    var negative = {
        x: [],
        y: [],
        mode: 'markers',
        name: 'negativ',
        marker: {
            color: '#dc3545',
            size: 10
        }
    };

    if(deviation.value.length !== null){
        for (var i = 0; i < deviation.value.length; i++){
            goal.x.push(deviation.date[i]);
            goal.y.push(0);

            if (Math.sign(deviation.value[i]) === -1){
                positiv.x.push(deviation.date[i]);
                positiv.y.push(deviation.value[i]);
            } else if (Math.sign(deviation.value[i]) === 0) {
                neutral.x.push(deviation.date[i]);
                neutral.y.push(deviation.value[i]);
            } else if (Math.sign(deviation.value[i]) === 1) {
                negative.x.push(deviation.date[i]);
                negative.y.push(deviation.value[i]);
            }
        }
    } else {
        console.log("No data found!");
    }

    var data = [goal, positiv, neutral, negative];

    var layout = {
        autosize: true,
        height: 450,
        width: 700,
        font: {
            color: '#ffffff'
        },
        paper_bgcolor: '#2C3E50',
        plot_bgcolor: '#2C3E50',
        margin: {
            l: 70,
            r: 70,
            b: 70,
            t: 70,
            pad: 7
        },
        title: 'Milestone Deviation',
        xaxis: {
            title: 'Dates',
            gridcolor: 'white'
        },
        yaxis: {
            title: 'Deviation',
            gridcolor: 'white'
        }
    };
    Plotly.newPlot("line-chart", data, layout);
}

function createPieChart(deviation)
{
    var earlier = 0;
    var intime = 0;
    var later = 0;
    var colorSelection = ['#3498db', '#f39c12', '#dc3545'];



    if(deviation.value.length !== null){
        for (var i = 0; i < deviation.value.length; i++){

            if (Math.sign(deviation.value[i]) === -1){
                earlier++;
            } else if (Math.sign(deviation.value[i]) === 0) {
                intime++;
            } else if (Math.sign(deviation.value[i]) === 1) {
                later++;
            }
        }
        earlier = (earlier / i) * 100;
        intime = (intime / i) * 100;
        later = (later / i) * 100;
        var data = [{
            values: [earlier, intime, later],
            labels: ['...earlier', '...in time', '...later'],
            type: 'pie',
            marker: {
                colors: colorSelection
            }
        }];
    } else {
        console.log("No data!");
    }

    var layout = {
        height: 450,
        width: 700,
        font: {
            color: '#ffffff'
        },
        paper_bgcolor: '#2C3E50',
        plot_bgcolor: '#2C3E50',
        margin: {
            l: 70,
            r: 70,
            b: 70,
            t: 70,
            pad: 7
        },
        title: 'Milestones reached...'
    };

    Plotly.newPlot("pie-chart", data, layout);
}