$(document).ready(function () {
	var $primary = '#5A8DEE';
	var $success = '#39DA8A';
	var $danger = '#FF5B5C';
	var $warning = '#FDAC41';
	var $info = '#00CFDD';
	var $label_color = '#475f7b';
	var $primary_light = '#E2ECFF';
	var $danger_light = '#ffeed9';
	var $gray_light = '#828D99';
	var $sub_label_color = "#596778";
	var $radial_bg = "#e7edf3";
	var $secondary = '#828D99';
	var $secondary_light = '#e7edf3';
	var $light_primary = "#E2ECFF";
	var analyticsBarChartOptions = {
		chart: {
			height: 204,
			type: 'bar',
			toolbar: {
				show: false
			}
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: '20%',
				endingShape: 'rounded'
			},
		},
		legend: {
			horizontalAlign: 'right',
			offsetY: -10,
			markers: {
				radius: 50,
				height: 8,
				width: 8
			}
		},
		dataLabels: {
			enabled: false
		},
		colors: [$primary, $primary_light],
		fill: {
			type: 'gradient',
			gradient: {
				shade: 'light',
				type: "vertical",
				inverseColors: true,
				opacityFrom: 1,
				opacityTo: 1,
				stops: [0, 70, 100]
			},
		},
		series: [{
			name: '2019',
			data: [80, 95, 150, 210, 140, 230, 300, 280, 130]
		}, {
			name: '2018',
			data: [50, 70, 130, 180, 90, 180, 270, 220, 110]
		}],
		xaxis: {
			categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false
			},
			labels: {
				style: {
					colors: $gray_light
				}
			}
		},
		yaxis: {
			min: 0,
			max: 300,
			tickAmount: 3,
			labels: {
				style: {
					color: $gray_light
				}
			}
		},
		legend: {
			show: false
		},
		tooltip: {
			y: {
				formatter: function (val) {
					return "$ " + val + " thousands"
				}
			}
		}
	}

	var analyticsBarChart = new ApexCharts(
		document.querySelector("#analytics-bar-chart"),
		analyticsBarChartOptions
	);

	analyticsBarChart.render();

	var multiRadialOptions = {
		chart: {
			height: 220,
			type: "radialBar",
		},
		colors: [$primary, $danger, $warning],
		series: [75, 80, 85],
		plotOptions: {
			radialBar: {
				offsetY: -10,
				hollow: {
					size: "40%"
				},
				track: {
					margin: 10,
					background: '#fff',
				},
				dataLabels: {
					name: {
						fontSize: '12px',
						color: [$secondary],
						fontFamily: "IBM Plex Sans",
						offsetY: 25,
					},
					value: {
						fontSize: '30px',
						fontFamily: "Rubik",
						offsetY: -15,
					},
					total: {
						show: true,
						label: 'Total Visits',
						color: $secondary
					}
				}
			}
		},
		stroke: {
			lineCap: "round",
		},
		labels: ['Target', 'Mart', 'Ebay']
	};

	var multiradialChart = new ApexCharts(
		document.querySelector("#multi-radial-chart"),
		multiRadialOptions
	);
	multiradialChart.render();
});