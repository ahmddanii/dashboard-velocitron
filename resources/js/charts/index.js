import { initMonthlyChart } from "./monthlyChart";
import { initYearlyChart } from "./yearlyChart";
import { initCategoryChart } from "./categoryChart";
import { initSegmentChart } from "./segmentChart";
import { initRegionChart } from "./regionChart";
import { initDssTrendChart } from "./dssTrendChart";

export function initCharts() {
    const context = document.getElementById("dashboard-context");

    if (!context) return;

    const data = JSON.parse(context.textContent);

    if (!data) return;

    initMonthlyChart(data.monthly);

    initYearlyChart(data.yearly);

    initCategoryChart(data.category);

    initSegmentChart(data.segment);

    initRegionChart(data.region, data.role);

    initDssTrendChart(data.dss_trend);
}
