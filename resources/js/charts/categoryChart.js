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
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                    maxBarThickness: 40,
                },

                {
                    label: "Profit",

                    data: categoryData.map((item) => item.total_profit),

                    backgroundColor: COLORS.greenLt,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                    maxBarThickness: 40,
                },
            ],
        },

        options: {
            ...defaultChartOptions,

            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            family: 'Inter',
                            size: 11,
                            weight: '600'
                        },
                        boxWidth: 8,
                        padding: 20,
                        color: 'rgba(148, 163, 184, 0.8)'
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: true
                }
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: 'rgba(148, 163, 184, 0.7)',
                        font: {
                            family: 'Inter',
                            size: 11,
                            weight: '500'
                        },
                    },
                },

                y: {
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false,
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: 'rgba(148, 163, 184, 0.7)',
                        font: {
                            family: 'Inter',
                            size: 11,
                        },
                        callback: (v) => "$" + (v / 1000).toFixed(0) + "K",
                        padding: 10
                    },
                },
            },
        },
    });
}
