# Test Tecnico - Frontend con backend Laravel

Ciao! 👋 Questo repository contiene un test tecnico frontend: un mini-portale turistico ("Vivi Bugliano") costruito con Nuxt 4 su un backend Laravel già pronto.

L'obiettivo è realizzare un'area pubblica con la lista e il dettaglio degli eventi (SSR + SEO) e un'area riservata dove gli utenti autenticati gestiscono i propri preferiti, curando in particolare la gestione della sessione/autenticazione in SSR, la qualità del codice, l'accessibilità e l'uso corretto dei design system (Bootstrap Italia per la parte pubblica, componenti shadcn-vue personalizzati per l'area riservata).

I paragrafi che seguono spiegano come installare, avviare e consegnare il progetto, oltre ai dettagli su cosa costruire e come viene valutato.

## Prerequisiti

L'installazione di questo progetto richiede i seguenti prerequisiti:
- Docker
- Docker Compose
- NodeJS
- npm

## Installazione e configurazione iniziale

### Backend Laravel API

Clonare il repository nella propria cartella di lavoro:

```sh
git clone git@github.com:gasparigit/test-tecnico-frontend.git
```

```sh
cd test-tecnico-frontend
```

Installare le dipendenze del progetto Laravel API eseguendo, in ordine:

```sh
cd api
```

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

```sh
./vendor/bin/sail up -d
```

```sh
./vendor/bin/sail composer install
```

```sh
./vendor/bin/sail artisan migrate --seed
```

Nel caso in cui la porta 8080 sia già in uso, modificare il file `.env` e cambiare la porta `APP_PORT` con un'altra porta libera. Aggiornare di conseguenza anche il file `.env` nella cartella `frontend` modificando la variabile `API_PROXY_TARGET`.

Verificare che l'API sia in esecuzione visitando `http://localhost:8080/api/events` nel browser.

### Frontend Nuxt 4

Installare le dipendenze del progetto Nuxt 4 eseguendo, in ordine:

```sh
cd ../frontend
```

```sh
npm install
```

```sh
npm run dev
```

Se tutto è stato configurato correttamente, il frontend sarà accessibile all'indirizzo `http://localhost:3000` e le chiamate a `/api` verranno inoltrate dal proxy di Nitro al backend Laravel (`http://localhost:8080/api`).

## Il progetto

Il Comune di Bugliano (fittizio) vuole un mini-portale turistico, "Vivi Bugliano": una parte pubblica con gli eventi del territorio e un'area riservata dove i cittadini registrati salvano i propri eventi preferiti.

È lo stesso tipo di progetto su cui lavoriamo ogni giorno: siti istituzionali e turistici, con vincoli di accessibilità e design system della PA.

### Cosa costruire

#### 1. Area pubblica (Bootstrap Italia)

- Lista eventi (`/eventi`): ricerca testuale, filtro per categoria, paginazione.
- Dettaglio evento (`/eventi/[slug]`): rotta dinamica, renderizzata in SSR, con meta tag SEO corretti (title, description, Open Graph).
- Usa i componenti di `bootstrap-italia` dove ha senso: header istituzionale, breadcrumb, card, chip, ecc.
- Se l'utente è autenticato, dalla lista può aggiungere/rimuovere un evento dai preferiti.

#### 2. Autenticazione OAuth2 (Laravel Passport)

- Login: `POST /api/login` con body `{ email, password }` → risposta `{ token, user }`. Il token (OAuth2 personal access token via Passport) va inviato nelle richieste protette come header `Authorization: Bearer <token>`.
- La sessione deve funzionare anche in SSR: una pagina protetta richiesta con un token valido deve renderizzare i dati dell'utente già lato server, senza flash di "non autenticato".
- Middleware per proteggere l'area riservata (redirect al login se ospite o token assente).
- Gestione della scadenza del token con rinnovo automatico e gestione degli errori 401 (non autenticato o token scaduto) e 422 (credenziali errate).
- Logout: `POST /api/logout` revoca il token corrente.

Vincolo: niente moduli auth preconfezionati (`@sidebase/nuxt-auth`, `nuxt-auth-utils`, ecc.).

#### 3. Area personale (shadcn-vue personalizzati + Tailwind)

- Pagina `/area-personale` protetta: lista dei preferiti con possibilità di rimozione (con dialog di conferma).
- Almeno 2 componenti shadcn-vue personalizzati: per "personalizzati" intendiamo varianti e token propri (es. palette del Comune) via `cva` e config Tailwind. Esempi: un Button con variante brand, una DataTable/Card per i preferiti, un Dialog di conferma.
- Bootstrap Italia non va usato qui: l'area riservata ha un suo design system basato su Tailwind.

#### 4. API (backend Laravel già pronto)

Il backend è un progetto Laravel (cartella `api/`) con autenticazione via **Laravel Passport** (token Bearer). Tutti gli endpoint sono sotto il prefisso `/api`. Le liste usano la paginazione standard di Laravel (`data`, `links`, `meta`) e i filtri di `spatie/laravel-query-builder`.

| Metodo | Endpoint                       | Auth | Comportamento                                                                                                                                                                                                                                                             |
| ------ | ------------------------------ | ---- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| POST   | `/api/login`                   | no   | Body `{ email, password }` → `{ token, user }`. 422 se credenziali errate                                                                                                                                                                                                 |
| POST   | `/api/logout`                  | sì   | Revoca il token corrente                                                                                                                                                                                                                                                  |
| GET    | `/api/user`                    | sì   | Dati dell'utente autenticato — 401 se ospite                                                                                                                                                                                                                              |
| GET    | `/api/settings`                | no   | Impostazioni del portale (nome, descrizione, logo, contatti, social)                                                                                                                                                                                                      |
| GET    | `/api/events`                  | no   | Lista paginata. Filtri: `filter[title]`, `filter[location]`, `filter[category]`, `filter[is_published]`; ordinamento `sort` (`title`, `starts_at`, `created_at`, default `starts_at`); `page`, `per_page` (default 15). Se autenticato, ogni evento include `is_favorite` |
| GET    | `/api/events/categories`       | no   | Categorie distinte degli eventi pubblicati, ordinate: `{ "data": ["cultura", ...] }`. Usata per popolare il filtro per categoria                                                                                                                                          |
| GET    | `/api/events/{slug}`           | no   | Dettaglio evento per slug, 404 se non esiste. Se autenticato include `is_favorite`                                                                                                                                                                                        |
| GET    | `/api/favorites`               | sì   | Eventi preferiti dell'utente (paginati)                                                                                                                                                                                                                                   |
| POST   | `/api/events/{event}/favorite` | sì   | Aggiunge ai preferiti (201)                                                                                                                                                                                                                                               |
| DELETE | `/api/events/{event}/favorite` | sì   | Rimuove dai preferiti                                                                                                                                                                                                                                                     |

`{event}` è l'ID numerico dell'evento (route model binding). Le richieste protette richiedono l'header `Authorization: Bearer <token>`.

Esempio JSON di un evento:

```json
{
  "id": 1,
  "title": "Sagra del Tartufo",
  "slug": "sagra-del-tartufo-123",
  "category": "enogastronomia",
  "description": "Tre giorni di degustazioni...",
  "location": "Piazza del Municipio",
  "starts_at": "2026-10-10T18:00:00.000000Z",
  "ends_at": "2026-10-10T20:00:00.000000Z",
  "is_published": true,
  "is_favorite": false,
  "created_at": "2026-08-01T10:00:00.000000Z",
  "updated_at": "2026-08-01T10:00:00.000000Z"
}
```

`is_favorite` è presente solo nelle richieste autenticate.

Utente demo: `mario.rossi@gmail.com` / `MarioRossi2026`. Durante l'installazione del progetto verranno creati 25 eventi di esempio.

### Vincoli tecnici

- Nuxt 4, Composition API, TypeScript.
- Tailwind CSS v4.
- shadcn-vue per l'area riservata, `bootstrap-italia` per l'area pubblica (vedi sopra).
- Accessibilità: per i siti della PA è un requisito di legge, non un bonus. Cura semantica, navigazione da tastiera, focus, ARIA dove serve.

### Priorità

1. Auth e sessione funzionante in SSR — è il cuore della prova
2. Area pubblica (lista + dettaglio, SSR/SEO)
3. Area riservata e componenti shadcn personalizzati
4. Rifiniture (empty state, loading, errori)

### Come valutiamo

In ordine di peso:
1. Gestione sessione/auth in SSR
2. Architettura e qualità del codice
3. Componenti (personalizzazione shadcn, uso corretto di bootstrap-italia)
4. Accessibilità e SEO

Bonus facoltativi (non richiesti):
- Test su auth flow o componenti (Vitest/Playwright)
- i18n IT/EN
- Ottimizzazioni performance
- Dark mode nell'area personale

## Chiusura e invio del progetto

Fermare il frontend con `Ctrl+C` nel terminale in cui gira `npm run dev`, poi fermare i container Laravel Sail:

```sh
cd ../api
```

```sh
./vendor/bin/sail down
```

Eliminare la cartella `./api/tmp`, che contiene i dati del database MariaDB (montata come volume su `/var/lib/mysql`) e non va inclusa nella consegna:

```sh
rm -rf ./tmp
```

Inviare il progetto in **uno** dei seguenti modi:

- **Zip via email:** comprimere la cartella del progetto ed inviarla via email
- **Fork Git:** creare un fork del repository e inviare il link
- **Cartella cloud:** caricare il progetto su una cartella condivisa (Google Drive, Dropbox, OneDrive, ecc.) e inviare il link

L'indirizzo email a cui inviare il file ZIP o il link è `developers@gruppogaspari.it`

Se possibile, aggiornare questo file README con eventuali note, scelte implementative, pensieri o problemi riscontrati.

## Decisioni tecniche

- **BFF (Backend For Frontend)** — lo strato server di Nuxt (Nitro) fa da intermediario tra browser e Laravel. Il token vive in un cookie **httpOnly**, non raggiunge mai il client: non leggibile da JavaScript (difesa XSS) e mai serializzato nell'HTML. Il cookie viaggia con la richiesta del documento, quindi il server conosce l'utente già in SSR (nessun flash di stato non autenticato). `useRequestFetch` propaga il cookie durante l'SSR, dove `$fetch` non lo farebbe.
- **`devProxy` sostituito da handler Nitro** — il `devProxy` esiste solo in sviluppo e non può leggere il cookie per iniettare il token. Gli handler Nitro funzionano anche in produzione e controllano l'intero flusso di autenticazione.
- **Isolamento dei due design system** — Bootstrap Italia (area pubblica) e Tailwind (area riservata) sono separati per layout: BI caricato via `useHead` nel layout pubblico, Tailwind importato solo nel layout dell'area personale. Così i reset CSS non si calpestano.
- **shadcn-vue in modalità manuale** — i componenti sono scritti a mano (pattern shadcn: cva + token) invece di usare il CLI, perché il modulo `shadcn-nuxt` impone Tailwind globale, che confliggerebbe con Bootstrap Italia.
- **Niente libreria di validazione (JustValidate)** — per i campi required ho preferito validazione reattiva con lo stato Vue, evitando una libreria imperativa che manipola il DOM in contrasto col modello dichiarativo di Vue.
- **Normalizzazione delle risposte nel BFF** — il layer server uniforma le risposte del backend (es. l'utente restituito in forme diverse tra `/login` e `/user`) così il client consuma sempre la stessa struttura.

## Componenti

**Area riservata (shadcn-vue, cva + token del Comune):**
- `Button` — varianti `brand` / `outline` / `ghost`, taglie `sm` / `md` / `lg`
- `Card` — varianti `elevated` / `outlined`
- `ConfirmDialog` — dialog accessibile (reka-ui) per la conferma di rimozione

I token (palette del Comune) sono definiti in `@theme` (Tailwind v4) e consumati dalle varianti cva.

**Area pubblica (Bootstrap Italia):** header istituzionale, breadcrumb, card evento, paginazione, select e input di ricerca, incapsulati in componenti Vue riutilizzabili.

## Accessibilità

- Form con errori annunciati (`role="alert"`, `aria-invalid`, `aria-describedby`) e label associate agli input.
- Dialog con focus trap, chiusura con Esc e attributi ARIA (via reka-ui).
- `aria-current` su breadcrumb e navigazione; `<button>` invece di `<a>` per le azioni.
- Elenco di definizioni (`<dl>`) semantico per i dati utente.

## Funzionalità extra

- **Dark mode** nell'area personale, con toggle e persistenza via cookie letto in SSR (nessun flash di tema al reload). Isolata all'area riservata.

## Limiti noti

- **Rinnovo token** — tra gli endpoint documentati non è previsto un endpoint di refresh, e il backend emette personal access token senza refresh token. La sessione è gestita via scadenza del cookie e re-login sul 401. Un rinnovo automatico richiederebbe un endpoint dedicato lato backend.
- **FOUT dei font** — i font di Bootstrap Italia sono caricati via JS (`loadFonts`), quindi c'è un breve flash al primo caricamento. Mitigabile con il preload dei pesi principali.
- **Validazione client** — volutamente minimale (campi required); la validazione autorevole resta quella del backend (risposte 422 di Laravel), già gestita.

## Struttura del progetto

```
server/api/auth/       handler di autenticazione (login, logout, me)
server/api/[...path]   proxy verso Laravel con iniezione del token
app/composables/       useAuth
app/middleware/        auth, guest
app/plugins/           idratazione utente, init Bootstrap Italia
app/components/        componenti area pubblica (Bootstrap Italia)
app/components/ui/     componenti shadcn (Button, Card, ConfirmDialog)
app/layouts/           default (pubblico), area-personale (Tailwind)
app/pages/             eventi, eventi/[slug], accedi, area-personale
app/utils/             cn, formatDate
app/types/             tipi condivisi (event, form, user, breadcrumb)
```