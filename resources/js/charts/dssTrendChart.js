import Chart from "chart.js/auto";

import { COLORS } from "./colors";

export function initDssTrendChart(data) {
    const canvas = document.getElementById("dssTrendChart");

    if (!canvas || !data?.length) return;

    new Chart(canvas, {
        type: "line",

        data: {
            labels: data.map((item) => item.date),

            datasets: [
                {
                    label: "Approved",

                    data: data.map((item) => item.approved),

                    borderColor: COLORS.green,

                    backgroundColor: COLORS.green,

                    tension: 0.4,

                    fill: false,
                },

                {
                    label: "Rejected",

                    data: data.map((item) => item.rejected),

                    borderColor: COLORS.red,

                    backgroundColor: COLORS.red,

                    tension: 0.4,

                    fill: false,
                },
            ],
        },

        options: {
            responsive: true,

            maintainAspectRatio: false,

            borderRadius: 8,
        },
    });
}
