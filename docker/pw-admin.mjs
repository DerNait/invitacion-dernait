import { chromium } from 'playwright';

const base = process.env.BASE_URL || 'http://nginx';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 } });
const page = await ctx.newPage();
const errors = [];
page.on('pageerror', (e) => errors.push('PAGEERROR: ' + e.message));

await page.goto(base + '/login', { waitUntil: 'networkidle' });
await page.fill('#email', process.env.ADMIN_EMAIL);
await page.fill('#password', process.env.ADMIN_PASSWORD);
await page.click('button[type=submit]');
await page.waitForURL('**/admin', { timeout: 15000 });
await page.waitForTimeout(1200);
await page.screenshot({ path: '/out/admin-desktop.png', fullPage: true });

const ctxM = await browser.newContext({ viewport: { width: 390, height: 844 }, storageState: await ctx.storageState() });
const pm = await ctxM.newPage();
await pm.goto(base + '/admin', { waitUntil: 'networkidle' });
await pm.waitForTimeout(1000);
await pm.screenshot({ path: '/out/admin-mobile.png', fullPage: true });

console.log('URL after login:', page.url());
console.log(errors.length ? errors.join('\n') : 'No page errors ✓');
await browser.close();
