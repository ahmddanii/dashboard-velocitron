import Chart from "chart.js/auto";

import { COLORS } from "./colors";
import { defaultChartOptions } from "./config";

export function initCategoryChart(categoryData) {
    const ctx = document.getElementById("categoryChart");

    if (!ctx || !categoryData?.length) return;

    new Chart(ctx, {
        type: "bar",

        data: {
            labels: categoryData.map((item) => item.category),

            datasets: [
                {
                    label: "Sales",

                    data: categoryData.map((item) => item.total_sales),

                    backgroundColor: COLORS.blueLt,

                    borderRadius: 4,
                },

                {
                    label: "Profit",

                    data: categoryData.map((item) => item.total_profit),

                    backgroundColor: COLORS.greenLt,

                    borderRadius: 4,
                },
            ],
        },

        options: {
            ...defaultChartOptions,

            plugins: {
                legend: {
                    position: "bottom",

                    labels: {
                        font: {
                            size: 10,
                        },

                        boxWidth: 10,
                        padding: 10,
                    },
                },
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },

                    ticks: {
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
                        font: {
                            size: 10,
                        },

                        callback: (v) => "$" + (v / 1000).toFixed(0) + "K",
                    },
                },
            },
        },
    });
}
