import Chart from "chart.js/auto";

import { COLORS } from "./colors";
import { defaultChartOptions } from "./config";

export function initMonthlyChart(monthlyData) {
    const ctx = document.getElementById("monthlyChart");

    if (!ctx || !monthlyData?.length) return;

    new Chart(ctx, {
        type: "line",

        data: {
            labels: monthlyData.map((item) => item.period),

            datasets: [
                {
                    label: "Sales",

                    data: monthlyData.map((item) => item.total_sales),

                    borderColor: COLORS.blue,

                    backgroundColor: "rgba(37,99,235,0.07)",

                    borderWidth: 2.5,

                    tension: 0.4,

                    fill: true,

                    pointRadius: 2,
                    pointHoverRadius: 6,
                    pointHitRadius: 16,
                },

                {
                    label: "Profit",

                    data: monthlyData.map((item) => item.total_profit),

                    borderColor: COLORS.green,

                    borderWidth: 2,

                    tension: 0.4,

                    fill: false,

                    pointRadius: 2,
                    pointHoverRadius: 6,
                    pointHitRadius: 16,
                },
            ],
        },

        options: {
            ...defaultChartOptions,

            interaction: {
                mode: "index",
                intersect: false,
            },

            plugins: {
                legend: {
                    display: false,
                },

                tooltip: {
                    backgroundColor: "#0f172a",

                    titleColor: "#ffffff",

                    bodyColor: "#cbd5e1",

                    padding: 12,

                    cornerRadius: 10,

                    callbacks: {
                        label: function (context) {
                            return (
                                context.dataset.label +
                                ": $" +
                                Number(context.parsed.y).toLocaleString()
                            );
                        },
                    },
                },
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },

                    ticks: {
                        color: "#94a3b8",

                        font: {
                            size: 10,
                        },
                    },
                },

                y: {
                    grid: {
                        color: "#f1f5f9",
                    },

                    ticks: {
                        color: "#94a3b8",

                        font: {
                            size: 10,
                        },

                        callback: function (value) {
                            return "$" + (value / 1000).toFixed(0) + "K";
                        },
                    },
                },
            },
        },
    });
}
