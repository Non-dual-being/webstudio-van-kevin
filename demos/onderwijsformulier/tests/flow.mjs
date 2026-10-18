import fs from 'node:fs';
import { launch, tab, origin, root, pause } from '../scripts/browser-session.mjs';

const results = [];
const browser = await launch();
try {
  for (const width of [390, 1440]) {
    const page = await tab(browser.port);
    const checks = [];
    const check = async (label, expression) => {
      const passed = Boolean(await page.evaluate(expression));
      checks.push({ label, passed });
      if (!passed) throw Error(`${width}: ${label}`);
    };
    const shot = async () => {}; // Beeldselectie van maximaal zes staat in dashboard-flow.mjs.
    const ready = () => page.until(`!!document.getElementById('bezoekdatum-visible')`);
    const press = async selector => { await page.tabTo(selector); await page.key('Enter'); };
    const choose = async (selector, index = 0) => {
      await press(selector);
      for (let i = 0; i < index; i++) await page.key('ArrowDown');
      await page.key('Enter');
    };
    const restart = async () => { await press('header .demo-reset'); await ready(); };
    const date = async day => {
      await press('#bezoekdatum-visible'); await page.key('ArrowDown');
      for (let i = 0; i < 35 && !await page.evaluate(`document.activeElement.dateObj?.getDate() === ${day}`); i++) await page.key('ArrowRight');
      await check('Kalenderdag met pijltjestoetsen bereikbaar', `document.activeElement.dateObj?.getDate() === ${day}`);
      await page.key('Enter');
      await page.until(`document.getElementById('bezoekdatum').value === '2031-10-${String(day).padStart(2,'0')}'`);
    };
    const morning = async () => {
      await date(15); await choose('#onderwijsSector');
      await page.until(`!!document.getElementById('programma')`); await choose('#programma');
      await page.until(`!!document.querySelector('.education-level-card__button')`); await press('.education-level-card__button');
      await page.until(`!!document.querySelector('.education-group-chip')`); await press('.education-group-chip');
      await page.until(`!!document.getElementById('aantalLeerlingen')`); await page.type('#aantalLeerlingen', '40');
      await page.until(`!!document.getElementById('aantalBegeleiders')`); await page.type('#aantalBegeleiders', '5');
      await page.until(`!!document.getElementById('voorwaardenAkkoord')`);
      await page.until(`document.querySelector('.price-quote-field')?.innerText.includes('290,00')`);
    };
    const hold = async endpoint => page.evaluate(`(() => {
      window.__originalFetch = window.fetch; window.__heldArrived = false; let used = false;
      window.fetch = async (input, init) => {
        if (!used && input === '/demo-api/${endpoint}') {
          used = true;
          const response = await window.__originalFetch(input, {...init, signal: undefined});
          window.__heldArrived = true;
          await new Promise(resolve => { window.__releaseResponse = resolve; });
          return response;
        }
        return window.__originalFetch(input, init);
      };
    })()`);
    const release = async () => { await page.evaluate(`window.fetch = window.__originalFetch; window.__releaseResponse()`); await pause(350); };
    try {
      await page.send('Emulation.setDeviceMetricsOverride', { width, height: width === 390 ? 844 : 1000, deviceScaleFactor: 1, mobile: width === 390 });
      await page.send('Page.navigate', { url: origin }); await ready();
      await check('Fictieve startgegevens', `document.getElementById('schoolnaam').value === 'Voorbeeldschool' && document.getElementById('email').value === 'bezoeker@example.invalid'`);
      await check('Geen horizontale overloop bij start', 'document.documentElement.scrollWidth <= innerWidth'); await shot('intro');
      await page.tabTo('#schoolnaam');
      await check('Zichtbare toetsenbordfocus', `document.activeElement.matches(':focus-visible') && getComputedStyle(document.activeElement).outlineStyle === 'solid'`); await shot('focus');
      await press('#bezoekdatum-visible'); await page.key('ArrowDown');
      await check('Beperkte en geblokkeerde dagen zichtbaar', `!!document.querySelector('[data-geo-agenda-kind="bookable_limited"]') && document.querySelectorAll('.flatpickr-disabled').length > 0`);
      await shot('calendar'); await page.key('Escape'); await morning();
      await check('Rooster unavailable zonder downloads', `document.body.innerText.includes('Deze demo bevat geen roosterbestanden.') && !document.querySelector('.roster-preview-card__download')`);
      await page.type('#aantalLeerlingen', '39'); await page.until(`document.body.innerText.includes('minimum van 40')`);
      await check('Leerlinggrens met foutmarkering', `document.getElementById('aantalLeerlingen').getAttribute('aria-invalid') === 'true'`); await shot('validation');
      await page.type('#aantalLeerlingen', '40'); await page.until(`!!document.getElementById('aantalBegeleiders')`);
      await page.type('#aantalBegeleiders', '2');
      await check('Begeleidergrens met foutmarkering', `document.getElementById('aantalBegeleiders').getAttribute('aria-invalid') === 'true'`);
      await page.type('#aantalBegeleiders', '5'); await page.until(`!!document.getElementById('voorwaardenAkkoord')`);
      await page.tabTo('#foodAndDrinkSelection-waterijsje'); await page.key(' ');
      await page.until(`!!document.querySelector('#foodAndDrinkSelection-waterijsje:checked')`);
      await page.type('.food-drink-selection-option.is-selected input[type="text"]', '3');
      await page.until(`document.querySelector('.price-quote-field')?.innerText.includes('293,45')`);
      await page.evaluate(`(() => { const real = window.fetch; window.__originalFetch = real; let used = false; window.fetch = (url,init) => { if (!used && url === '/demo-api/quote') { used = true; return Promise.reject(new TypeError('Fictieve netwerkfout')); } return real(url,init); }; })()`);
      await page.tabTo('#foodAndDrinkSelection-waterijsje'); await page.key(' ');
      await page.until(`document.querySelector('.price-quote-notice--error')?.innerText.includes('Controleer de verbinding')`);
      await check('Prijsfout laat invoer behouden', `document.getElementById('aantalLeerlingen').value === '40'`);
      await page.evaluate('window.fetch = window.__originalFetch');
      await page.tabTo('#foodAndDrinkSelection-waterijsje'); await page.key(' ');
      await page.type('.food-drink-selection-option.is-selected input[type="text"]', '3');
      await page.until(`document.querySelector('.price-quote-field')?.innerText.includes('293,45')`);
      await check('Akkoord vereist', `document.querySelector('button[type="submit"]').disabled`);
      await page.type('textarea#opmerkingen', 'Fictieve notitie. '.repeat(37).slice(0, 600));
      await check('600 tekens bewerkbaar en begrensd', `document.querySelector('textarea#opmerkingen').value.length === 600 && document.querySelector('textarea#opmerkingen').maxLength === 600`);
      await page.evaluate(`document.querySelector('.price-quote-field').scrollIntoView({block:'start'})`); await shot('quote');
      await page.evaluate(`document.querySelector('textarea#opmerkingen').scrollIntoView({block:'center'})`); await shot('comments');
      await check('Geen overloop volledig formulier', 'document.documentElement.scrollWidth <= innerWidth');
      await page.tabTo('#voorwaardenAkkoord'); await page.key(' '); await press('button[type="submit"]'); await page.until(`!!document.querySelector('.demo-success')`);
      await check('Eerlijk succes en focus', `document.querySelector('.demo-success').innerText.includes('Demo afgerond.') && document.querySelector('.demo-success').innerText.includes('Er is geen echte boeking gemaakt of e-mail verstuurd.') && document.activeElement.matches('.demo-success')`); await shot('success');
      await restart();
      await check('Formulier en kalender gereset', `document.getElementById('bezoekdatum').value === '' && !document.querySelector('textarea') && !document.querySelector('.demo-success') && document.querySelectorAll('.flatpickr-calendar').length === 1`);
      await page.evaluate(`(() => { const real = window.fetch; window.__originalFetch = real; let used = false; window.fetch = (url,init) => { if (!used && url === '/demo-api/rules') { used = true; return Promise.resolve(new Response(JSON.stringify({ok:false}),{status:503,headers:{'Content-Type':'application/json'}})); } return real(url,init); }; })()`);
      await press('header .demo-reset'); await page.until(`!!document.querySelector('[role="alert"]')`);
      await check('Begrijpelijke API-fout', `document.body.innerText.includes('De demo kon niet laden.')`); await shot('api-error');
      await page.evaluate('window.fetch = window.__originalFetch'); await restart();
      // Testadapter negeert abort bewust: echte antwoorden komen pas na reset aan.
      await hold('dates'); await press('header .demo-reset'); await page.until('window.__heldArrived'); await restart(); await release();
      await check('Late kalender genegeerd', `document.getElementById('bezoekdatum').value === '' && document.querySelectorAll('.flatpickr-calendar').length === 1`);
      await morning(); await hold('quote'); await page.tabTo('#foodAndDrinkSelection-waterijsje'); await page.key(' '); await page.until('window.__heldArrived'); await restart(); await release();
      await check('Late prijs genegeerd', `!document.querySelector('.price-quote-field') && !document.getElementById('aantalLeerlingen') && document.getElementById('bezoekdatum').value === ''`);
      await morning(); await hold('complete'); await page.tabTo('#voorwaardenAkkoord'); await page.key(' '); await press('button[type="submit"]'); await page.until('window.__heldArrived'); await restart(); await release();
      await check('Late afronding genegeerd', `!document.querySelector('.demo-success') && document.getElementById('schoolnaam').value === 'Voorbeeldschool'`);
      await date(8); await choose('#onderwijsSector'); await page.until(`!!document.getElementById('programma')`); await choose('#programma');
      await page.until(`!!document.querySelector('.education-level-card__button')`); await press('.education-level-card__button');
      await page.until(`!!document.querySelector('.education-group-chip')`); await press('.education-group-chip');
      await page.until(`!!document.getElementById('aantalLeerlingen')`); await page.type('#aantalLeerlingen', '41');
      await check('Beperkte capaciteit begrenst invoer', `document.getElementById('aantalLeerlingen').getAttribute('aria-invalid') === 'true' && document.body.innerText.includes('maximaal 40')`);
      await page.type('#aantalLeerlingen', '40'); await page.until(`!!document.getElementById('aantalBegeleiders')`); await page.type('#aantalBegeleiders', '5');
      await choose('#onderwijsSector', 1);
      await page.until(`!document.getElementById('aantalLeerlingen') && !document.querySelector('.education-group-chip--selected')`);
      await check('Sectorwissel wist vervolgkeuzes', `!document.getElementById('aantalLeerlingen') && !document.querySelector('.education-group-chip--selected')`);
      await press('#programma');
      await check('Ochtend verdwijnt bij voortgezet onderwijs', `!document.querySelector('#programma-listbox').innerText.includes('Ochtendprogramma')`);
      await page.key('Enter'); await page.until(`document.querySelectorAll('.education-level-card').length === 5`);
      await press('.education-level-card:nth-child(3) .education-level-card__button');
      await page.until(`!!document.querySelector('.education-group-chip')`); await press('.education-group-chip:nth-child(2)');
      await page.until(`!!document.querySelector('.education-module-choice')`); await press('.education-module-choice');
      await check('Module geldig bij HAVO2', `document.querySelector('.education-module-choice.is-selected')?.innerText.includes('Energie ontdekken')`);
      await press('.education-group-chip:nth-child(1)');
      await page.until(`!document.querySelector('.education-module-choice.is-selected') && !document.getElementById('aantalLeerlingen')`);
      await check('Groepswissel verwijdert ongeldige module', `![...document.querySelectorAll('.education-module-choice')].some(e=>e.innerText.includes('Energie ontdekken')) && !document.querySelector('.education-module-choice.is-selected') && !document.getElementById('aantalLeerlingen')`);
      await press('.education-level-card:nth-child(3) .education-level-card__button');
      await press('.education-level-card:nth-child(1) .education-level-card__button');
      await page.until(`!document.querySelector('.education-group-chip--selected')`);
      await check('Niveauwissel wist oude groepen', `!document.querySelector('.education-group-chip--selected') && !document.querySelector('.education-module-choice.is-selected')`);
      await check('Niveaunamen en status passen op mobiel', `[...document.querySelectorAll('.education-level-card__label,.education-level-card__status')].every(e=>e.scrollWidth<=e.clientWidth+1)`);
      await shot('selection');
      await date(9); await press('#programma');
      await check('Datumwissel behoudt alleen passend programma', `!document.querySelector('#programma-listbox').innerText.includes('Ochtendprogramma') && !document.getElementById('aantalLeerlingen')`);
      await page.key('Escape'); await restart();
      const storage = await page.evaluate(`(async () => ({local:Object.keys(localStorage),session:Object.keys(sessionStorage),db:await indexedDB.databases(),caches:await caches.keys(),workers:(await navigator.serviceWorker.getRegistrations()).length,history:history.state,cookies:document.cookie}))()`);
      if (storage.local.length || storage.session.length || storage.db.length || storage.caches.length || storage.workers || storage.history || storage.cookies) throw Error('Onverwachte browseropslag');
      const cookies = (await page.send('Network.getCookies', { urls: [origin] })).cookies;
      const externalRequests = page.requests.filter(r => r.origin !== origin && r.origin !== 'null');
      if (cookies.length || externalRequests.length || page.errors.length) throw Error('Cookies, externe requests of JavaScript-fout');
      for (const endpoint of ['rules', 'dates', 'policy', 'programs']) if (page.requests.filter(r => r.path === '/demo-api/' + endpoint).length < 5) throw Error('Oude cache: ' + endpoint);
      const denied = await page.evaluate(`(async () => { const out=[]; for (const path of ['/.env','/server/catalog.json','/src/Validation/FormRules.php','/booking/validatie.php','/api/getDisabledDates.php','/assets/booking/roosters/example.pdf']) { const r=await fetch(path); out.push({path,status:r.status}); } return out; })()`);
      if (denied.some(r => r.status !== 404)) throw Error('Niet-demo-route bereikbaar');
      results.push({width,checks,storage,cookies:cookies.length,externalRequests,denied,apiRequests:page.requests.filter(r=>r.path.startsWith('/demo-api/')).length,errors:page.errors});
    } catch (error) { await shot('failure'); results.push({width,checks,error:String(error),errors:page.errors}); throw error; }
    finally { fs.mkdirSync(root+'/artifacts',{recursive:true}); fs.writeFileSync(root+'/artifacts/browser-results.json',JSON.stringify(results,null,2)); await page.close(); }
  }
} finally { await browser.stop(); }
console.log(JSON.stringify(results.map(r=>({width:r.width,checks:r.checks.length,externalRequests:r.externalRequests.length,cookies:r.cookies,errors:r.errors.length})),null,2));
