import { chromium } from 'playwright';

const base = process.env.BASE_URL || 'http://nginx';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 390, height: 844 } });
const page = await ctx.newPage();
const errors = [];
page.on('pageerror', (e) => errors.push('PAGEERROR: ' + e.message));
page.on('console', (m) => m.type() === 'error' && errors.push('CONSOLE: ' + m.text()));

await page.goto(base + '/', { waitUntil: 'networkidle' });

const height = await page.evaluate(() => document.body.scrollHeight);
for (let y = 0; y < height; y += 600) {
    await page.evaluate((v) => window.scrollTo(0, v), y);
    await page.waitForTimeout(300);
}
await page.waitForTimeout(700);

for (const id of ['ubicacion', 'parqueo', 'comida', 'dresscode']) {
    const el = await page.$('#' + id);
    if (el) {
        await el.scrollIntoViewIfNeeded();
        await page.waitForTimeout(700);
        await el.screenshot({ path: `/out/section-${id}.png` });
        console.log(`#${id} captured`);
    } else {
        console.log(`#${id} NOT FOUND`);
    }
}
console.log(errors.length ? errors.join('\n') : 'No errors ✓');
await browser.close();
