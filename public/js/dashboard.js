$(document).ready(async function() {
  // chiffre d'affaire du mois
  let ca_month = 0
  let ca_year= 0
  let ca_week = 0

  let previous_ca_month = 0
  let previous_ca_year= 0
  let previous_ca_week = 0

  let avgPerDay = [0, 0, 0, 0, 0 , 0, 0]
  let purchasePerDay = [0, 0, 0, 0, 0, 0, 0]


  const TODAY = moment()
  const DAY = TODAY.date()
  const MONTH = TODAY.month()+1
  const YEAR = TODAY.year()

  /*
    YEAR BOUNDARIES
   */
  const YEAR_START_DATE = moment(`01/01/${YEAR}`, `DD/MM/YYYY`);
  const YEAR_END_DATE = moment(`31/12/${YEAR}`, `DD/MM/YYYY`);

  const PREVIOUS_YEAR_START_DATE = moment(`01/01/${YEAR-1}`, `DD/MM/YYYY`);
  const PREVIOUS_YEAR_END_DATE = moment(`31/12/${YEAR-1}`, `DD/MM/YYYY`);

  $(".year-number").text(YEAR)
  $(".previous-year-number").text(YEAR-1)

  /*
    MONTH BOUNDARIES
   */
  const MONTH_START_DATE = moment(`01/${MONTH}/${YEAR}`, `DD/MM/YYYY`);
  const MONTH_END_DATE = moment(`${TODAY.daysInMonth()}/${MONTH}/${YEAR}`, `DD/MM/YYYY`);

  $(".month-number").text(TODAY.format("MMMM") +" (2020)")
  $(".previous-month-number").text(TODAY.clone().subtract(1, 'month').format("MMMM")+" (2020)")

  let previous_month_number = 0
  if(MONTH == 1) {
    previous_month_number = 11
  } else {
    previous_month_number = MONTH - 1
  }
  const PREVIOUS_MONTH_START_DATE = moment(`01/${previous_month_number}/${YEAR}`, `DD/MM/YYYY`);
  const PREVIOUS_MONTH_END_DATE = moment(`${PREVIOUS_MONTH_START_DATE.daysInMonth()}/${previous_month_number}/${YEAR}`, `DD/MM/YYYY`);

  /*
    WEEK BOUNDARIES
   */
  const WEEK_START_DATE = TODAY.clone().startOf('week');
  const WEEK_END_DATE = TODAY.clone().endOf('week');

  const PREVIOUS_WEEK_START_DATE = TODAY.clone().subtract(1, 'week').startOf('week');
  const PREVIOUS_WEEK_END_DATE = TODAY.clone().subtract(1, 'week').endOf('week');

  $(".week-number").text("Semaine "+TODAY.isoWeek()+" (2020)")
  $(".previous-week-number").text("Semaine "+(TODAY.isoWeek()-1)+" (2020)")

  let purchases = await getPurchases().catch(console.error)

  purchases["hydra:member"].forEach(purchase => {

    total_amount = purchase.total
    purchase_date = new Date(purchase.date)
    let dayOfWeek = moment(purchase_date).clone().day()

    if(moment(purchase_date).isBetween(YEAR_START_DATE, YEAR_END_DATE)) {
      ca_year += purchase.total
      if(moment(purchase_date).isBetween(MONTH_START_DATE, MONTH_END_DATE)) {
        ca_month += purchase.total
        if(moment(purchase_date).isBetween(WEEK_START_DATE, WEEK_END_DATE)) {
          ca_week += purchase.total
          purchasePerDay[dayOfWeek] += 1
        }
      }
    }

    if(moment(purchase_date).isBetween(PREVIOUS_YEAR_START_DATE, PREVIOUS_YEAR_END_DATE)) {
      previous_ca_year += purchase.total
    }


    if(moment(purchase_date).isBetween(PREVIOUS_MONTH_START_DATE, PREVIOUS_MONTH_END_DATE)) {
      previous_ca_month += purchase.total
      avgPerDay[dayOfWeek] += 1
    }

    if(moment(purchase_date).isBetween(PREVIOUS_WEEK_START_DATE, PREVIOUS_WEEK_END_DATE)) {
      previous_ca_week += purchase.total
    }
  })

  $(".year-ca").text(`${ca_year} €`)
  $(".month-ca").text(`${ca_month} €`)
  $(".week-ca").text(`${ca_week} €`)

  $(".previous-year-ca").text(`${previous_ca_year} €`)
  $(".previous-month-ca").text(`${previous_ca_month} €`)
  $(".previous-week-ca").text(`${previous_ca_week} €`)

  function drawChart(chartData) {

    //chart setup
    var svg = d3.select("svg"),
        margin = {top: 80, right: 10, bottom: 80, left: 80},
        width = svg.attr("width") - margin.left - margin.right,
        height = svg.attr("height") - margin.top - margin.bottom,
        g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    let maxAverage = Math.max.apply(Math, chartData.map(function(o) { return o.Moyenne; }));
    let maxValue = Math.max.apply(Math, chartData.map(function(o) { return o.Semaine; }));
    maxValue > maxAverage ? maxDomain = maxValue : maxDomain = maxAverage
    maxDomain += maxValue + 20


    //y position calculation function
    var y = d3.scaleLinear()
          .domain([0, maxDomain])
          .range([height, 0]);

    var x0 = d3.scaleBand()  // domain defined below
          .rangeRound([0, width])
          .paddingInner(0.1)
          .paddingOuter(0.1);

    var x1 = d3.scaleBand()  // domain and range defined below
        .paddingOuter(0.25)
        .paddingInner(0.15);

    //blue, tan, red colors
    var z = d3.scaleOrdinal()
            .range(["#354B5F", "#BC151E"]);

    //reference to the y axis
    //axisLeft put labels on left side
    //ticks(n) refers to # of increment marks on the axis
    const yAxis = d3.axisLeft(y).ticks(7);

    //examine first object, retrieve the keys, and discard the first key
    //return resulting array of keys
    // [ "2017 Q1", "2017 Q2", "2017 Q3 First Estimate"]
    var subCategories = Object.keys(chartData[0]).slice(1);

    //use new array from just the Category values for the bottom x-axis
    x0.domain(chartData.map( d =>  d.Day ));

    //array of quarterly value names, fitted in the available bottom categories (x0.bandwidth())
    x1.domain(subCategories).rangeRound([0, x0.bandwidth()])

    // Add bar chart
    var selection = g.selectAll("g")
      .data(chartData)
      .enter().append("g")
      .attr("transform", d => "translate(" + x0(d.Day) + ",0)" )

    selection.selectAll("rect")
    //Use map function with the subCategories array and the chartData array
      .data(function(d) { return subCategories.map(function(key) { return {key: key, value: d[key]}; }); })
      .enter().append("rect")
      .attr("x", d => x1(d.key) )
      //If the value is negative, put the top left corner of the rect bar on the zero line
      .attr("y", d => (d.value<0 ? y(0) : y(d.value)) )
      .attr("width", x1.bandwidth())
      .attr("height", d => Math.abs(y(d.value) - y(0)) )
      .attr("fill", d => z(d.key) )

    //can not nest the text element inside the rect element !
    selection.selectAll("text")
      .data(function(d) { return subCategories.map(function(key) { return {key: key, value: d[key]}; }); })
      .enter().append("text")
      .attr("x", d => x1(d.key) )
      //offset the position of the y value (positive / negative) to have the text over/under the rect bar
      .attr("y", d => d.value<=0 ? y(0) - (y(4) - (Math.abs(y(d.value) - y(0)) + 20)) : y(d.value) - 10)
      .style('fill', d => z(d.key))
      .style('font-size', '1em')
      //make sure one just decimal place is displayed
      .text(d => {
        if(d.value != 0) {
          return Number.parseInt(d.value)
        } else {
          return ""
        }
      })

      //add the x-axis
      g.append("g")
        .attr("class", "axis")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x0))
        .selectAll(".tick text")
        //use wrap function to wrap long lines in labels
        .call(wrap, x0.bandwidth());

      //add the y-axis - notice it does not have css class 'axis'
      g.append('g')
        .call(yAxis)

      //idenitfy zero line on the y axis.
      g.append("line")
        .attr("y1", y(0))
        .attr("y2", y(0))
        .attr("x1", 0)
        .attr("x2", width)
        .attr("stroke", "black");

      /*
      * Legend
      */
      let legend = g.append("g")
        .attr("font-family", "sans-serif")
        .attr("font-size", 13)
        .attr("text-anchor", "end")
        .selectAll("g")
        .data(subCategories)
        .enter().append("g")
        .attr("transform", function(d, i) { return "translate(0," + i * 24 + ")"; });

      legend.append("rect")
        .attr("x", width - 142)
        .attr("width", 8)
        .attr("height", 8)
        .attr("fill", z);

      legend.append("text")
        .attr("x", d => d.length > 7 ? (width + 5) : (width - 80))
        .attr("y", 5.5)
        .attr("dy", "0.22em")
        .text(d => (d));

      svg.append('text')
        .attr('x', width / 2 + margin.left )
        .attr('y', height + margin.top + margin.bottom - 20)
        .attr('text-anchor', 'middle')
        .text('Nombre de commandes par jour de la semaine')

      svg.append('text')
        .attr('x', - (height / 2) - margin.top)
        .attr('y', margin.left / 2.4)
        .attr('transform', 'rotate(-90)')
        .attr('text-anchor', 'middle')
        .text('Nombre de commande')

      function wrap(text, width) {

      text.each(function() {
        var text = d3.select(this),
            words = text.text().split(/\s+/).reverse(),
            word,
            line = [],
            lineNumber = 0,
            lineHeight = 1.1, // ems
            y = text.attr("y"),
            dy = parseFloat(text.attr("dy")),
            tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
        while (word = words.pop()) {
          line.push(word);
          tspan.text(line.join(" "));
          if (tspan.node().getComputedTextLength() > width) {
            line.pop();
            tspan.text(line.join(" "));
            line = [word];
            tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
          }
        }
      });
    }
  }

  function getPurchases(data) {

      return new Promise(function (resolve, reject) {
          $.ajax({
              url: `http://saladetomateoignons.ddns.net/api/purshases`,
              type: 'GET',
              success: function (res) {
                  resolve(res)
              },
              error: function (err) {
                  reject(err)
              }
          });
      });
  }

  let chartData = []
  for(let i = 0; i < avgPerDay.length; i++) {
    avgPerDay[i] = avgPerDay[i] / 4;
    let obj = {
      "Day": moment().day(i).format("dddd"),
      "Moyenne": avgPerDay[i],
      "Semaine": purchasePerDay[i]
    }
    chartData.push(obj)
  }



  drawChart(chartData)
})
