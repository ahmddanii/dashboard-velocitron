import Chart from "chart.js/auto";

import { COLORS } from "./colors";
import { defaultChartOptions } from "./config";

export function initRegionChart(regionData, role) {
    /*
    |--------------------------------------------------------------------------
    | Doughnut
    |--------------------------------------------------------------------------
    */

    const doughnutCtx = document.getElementById("regionChart");

    if (
        doughnutCtx &&
        ["head-analytics", "key-account-manager"].includes(role)
    ) {
        new Chart(doughnutCtx, {
            type: "doughnut",

            data: {
                labels: regionData.map((item) => item.region),

                datasets: [
                    {
                        data: regionData.map((item) => item.total_sales),

                        backgroundColor: [
                            COLORS.blue,
                            COLORS.green,
                            COLORS.amber,
                            COLORS.red,
                        ],

                        borderWidth: 0,
                        hoverOffset: 6,
                    },
                ],
            },

            options: {
                ...defaultChartOptions,

                cutout: "65%",

                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Financial Controller
    |--------------------------------------------------------------------------
    */

    if (doughnutCtx && role === "financial-controller") {
        new Chart(doughnutCtx, {
            type: "bar",

            data: {
                labels: regionData.map((item) => item.region),

                datasets: [
                    {
                        label: "Sales",

                        data: regionData.map((item) => item.total_sales),

                        backgroundColor: COLORS.blueLt,

                        borderRadius: 4,
                    },

                    {
                        label: "Profit",

                        data: regionData.map((item) => item.total_profit),

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
                        },
                    },
                },

                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },

                    y: {
                        grid: {
                            color: "#f1f5f9",
                        },

                        ticks: {
                            callback: (v) => "$" + (v / 1000).toFixed(0) + "K",
                        },
                    },
                },
            },
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Logistics Officer
    |--------------------------------------------------------------------------
    */

    const regionBarCtx = document.getElementById("regionBarChart");

    if (regionBarCtx && role === "logistics-officer") {
        new Chart(regionBarCtx, {
            type: "bar",

            data: {
                labels: regionData.map((item) => item.region),

                datasets: [
                    {
                        label: "Total Orders",

                        data: regionData.map((item) => item.total_orders),

                        backgroundColor: [
                            COLORS.blue,
                            COLORS.green,
                            COLORS.amber,
                            COLORS.red,
                        ],

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
}
