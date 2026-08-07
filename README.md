# Test Tecnico - Frontend con backend Laravel

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
cd test-tecnico-frontend
```

Installare le dipendenze del progetto Laravel API eseguendo, in ordine:

```sh
cd api
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail artisan migrate --seed
```

Nel caso in cui la porta 8080 sia già in uso, modificare il file `.env` e cambiare la porta `APP_PORT` con un'altra porta libera. Aggiornare di conseguenza anche il file `.env` nella cartella `frontend` modificando la variabile `API_PROXY_TARGET`.

Verificare che l'API sia in esecuzione visitando `http://localhost:8080/api/events` nel browser.

### Frontend Nuxt 4

Installare le dipendenze del progetto Nuxt 4 eseguendo, in ordine:

```sh
cd ../frontend
npm install
npm run dev
```

Se tutto è stato configurato correttamente, il frontend sarà accessibile all'indirizzo `http://localhost:3000` e le chiamate API saranno in grado di essere effettuate tramite il proxy di Nitro verso l'indirizzo `http://localhost:3000/api`.

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