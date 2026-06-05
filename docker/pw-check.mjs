import { chromium } from 'playwright';

const base = process.env.BASE_URL || 'http://nginx';
const browser = await chromium.launch();

async function check(path, file, viewport) {
    const ctx = await browser.newContext({ viewport });
    const page = await ctx.newPage();
    const errors = [];
    page.on('console', (m) => m.type() === 'error' && errors.push(m.text()));
    page.on('pageerror', (e) => errors.push('PAGEERROR: ' + e.message));

    const resp = await page.goto(base + path, { waitUntil: 'networkidle', timeout: 30000 });
    await page.waitForTimeout(1500);
    const appHtml = await page.$eval('#app', (el) => el.innerHTML.length).catch(() => 0);
    await page.screenshot({ path: `/out/${file}`, fullPage: true });
    console.log(`\n[${path}] status=${resp.status()} #app innerHTML length=${appHtml}`);
    console.log(errors.length ? 'CONSOLE ERRORS:\n' + errors.join('\n') : 'No console errors ✓');
    await ctx.close();
    return { appHtml, errors };
}

const mobile = { width: 390, height: 844 };
const desktop = { width: 1280, height: 800 };

await check('/', 'invitation-mobile.png', mobile);
await check('/', 'invitation-desktop.png', desktop);
await check('/login', 'login-mobile.png', mobile);

await browser.close();
