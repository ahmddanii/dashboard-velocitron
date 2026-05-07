import Chart from "chart.js/auto";

import { COLORS } from "./colors";
import { defaultChartOptions } from "./config";

export function initYearlyChart(yearlyData) {
    const ctx = document.getElementById("yearlyChart");

    if (!ctx || !yearlyData?.length) return;

    new Chart(ctx, {
        type: "bar",

        data: {
            labels: yearlyData.map((item) => item.year),

            datasets: [
                {
                    label: "Total Sales",

                    data: yearlyData.map((item) => item.total_sales),

                    backgroundColor: [
                        COLORS.blueLt,
                        "#60a5fa",
                        "#3b82f6",
                        COLORS.blue,
                    ],

                    borderRadius: 6,
                },
            ],
        },

        options: {
            ...defaultChartOptions,

            plugins: {
                legend: {
                    display: false,
                },
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },

                    ticks: {
                        font: {
                            size: 11,
                        },

                        color: "#64748b",
                    },
                },

                y: {
                    grid: {
                        color: "#f1f5f9",
                    },

                    ticks: {
                        font: {
                            size: 10,
                        },

                        color: "#94a3b8",

                        callback: (v) => "$" + (v / 1000).toFixed(0) + "K",
                    },
                },
            },
        },
    });
}
