import Chart from "chart.js/auto";

import { COLORS } from "./colors";
import { defaultChartOptions } from "./config";

export function initSegmentChart(segmentData) {
    const ctx = document.getElementById("segmentChart");

    console.log("SEGMENT DATA:", segmentData);
    if (!ctx || !segmentData?.length) return;

    new Chart(ctx, {
        type: "bar",

        data: {
            labels: segmentData.map((item) => item.segment),

            datasets: [
                {
                    label: "Total Sales",

                    data: segmentData.map((item) => item.total_sales),

                    backgroundColor: [COLORS.blue, COLORS.cyan, COLORS.purple],

                    borderRadius: 4,
                },
            ],
        },

        options: {
            ...defaultChartOptions,

            indexAxis: "y",

            plugins: {
                legend: {
                    display: false,
                },
            },

            scales: {
                x: {
                    grid: {
                        color: "#f1f5f9",
                    },

                    ticks: {
                        callback: (v) => "$" + (v / 1000).toFixed(0) + "K",
                    },
                },

                y: {
                    grid: {
                        display: false,
                    },
                },
            },
        },
    });
}
