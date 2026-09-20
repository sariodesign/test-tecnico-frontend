<script lang="ts" setup>
const route = useRoute()
const { user, isLoggedIn, logout } = useAuth()

const isEventiActive = computed(() => route.path.startsWith('/eventi'))

const { locale, setLocale } = useI18n()

/* const langDropdownRef = ref<HTMLElement | null>(null)

onMounted(async () => {
  const { Dropdown } = await import('bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js')
  if (langDropdownRef.value) {
    Dropdown.getOrCreateInstance(langDropdownRef.value)
  }
}) */

const isLangOpen = ref(false)

function selectLang(code: 'it' | 'en') {
  setLocale(code)
  isLangOpen.value = false
}
</script>

<template>
  <header class="it-header-wrapper">
    <div class="it-header-slim-wrapper">
      <div class="container-xxl">
        <div class="row">
          <div class="col-12">
            <div class="it-header-slim-wrapper-content">
              <a class="d-lg-block navbar-brand" href="#">Ente appartenenza</a>
              <div class="it-header-slim-right-zone">
                <!-- <div class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" ref="langDropdownRef" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Selezione lingua: lingua selezionata</span>
                    <span>ITA</span>
                    <svg class="icon d-none d-lg-block"><use href="/sprites.svg#it-expand"/></svg>
                  </a>
                  <div class="dropdown-menu">
                    <div class="row">
                      <div class="col-12">
                        <div class="link-list-wrapper">
                          <ul class="link-list">
                            <li>
                              <button type="button" class="dropdown-item list-item bg-white text-primary" @click="setLocale('it')">
                                <span>ITA <span v-if="locale === 'it'" class="visually-hidden">selezionata</span></span>
                              </button>
                            </li>
                            <li>
                              <button type="button" class="dropdown-item list-item bg-white text-primary" @click="setLocale('en')">
                                <span>ENG <span v-if="locale === 'en'" class="visually-hidden">selezionata</span></span>
                              </button>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> -->
                <div class="nav-item dropdown">
                  <button
                    type="button"
                    class="nav-link dropdown-toggle"
                    :aria-expanded="isLangOpen"
                    @click="isLangOpen = !isLangOpen"
                  >
                    <span class="visually-hidden">Selezione lingua: lingua selezionata</span>
                    <span>{{ locale === 'it' ? 'ITA' : 'ENG' }}</span>
                    <svg class="icon d-none d-lg-block"><use href="/sprites.svg#it-expand"></use></svg>
                  </button>
                  <div class="dropdown-menu" :class="{ show: isLangOpen }">
                    <div class="link-list-wrapper">
                      <ul class="link-list">
                        <li>
                          <button type="button" class="dropdown-item list-item" @click="selectLang('it')">
                            <span>ITA <span v-if="locale === 'it'" class="visually-hidden">selezionata</span></span>
                          </button>
                        </li>
                        <li>
                          <button type="button" class="dropdown-item list-item" @click="selectLang('en')">
                            <span>ENG <span v-if="locale === 'en'" class="visually-hidden">selezionata</span></span>
                          </button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <NuxtLink v-if="!isLoggedIn" class="btn btn-primary btn-icon mr-2" to="/accedi">
                  <span class="rounded-icon">
                    <svg class="icon icon-primary">
                      <use href="/sprites.svg#it-user"/>
                    </svg>
                  </span>
                  <span class="d-none d-lg-block">Accedi all'area personale</span>
                </NuxtLink>
                <template v-else>
                  <NuxtLink class="btn btn-primary btn-icon mr-2" to="/area-personale">
                    <span class="rounded-icon">
                      <svg class="icon icon-primary">
                        <use href="/sprites.svg#it-user"/>
                      </svg>
                    </span>
                    <span class="d-none d-lg-block">Area personale di {{ user?.name }}</span>
                  </NuxtLink>
                  <button class="btn btn-primary btn-icon" @click="logout">
                    <svg class="icon icon-white">
                      <use href="/sprites.svg#it-logout"/>
                    </svg>
                    <span class="d-none d-lg-block">Esci</span>
                  </button>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="it-nav-wrapper">
      <div class="it-header-center-wrapper">
        <div class="container-xxl">
          <div class="row">
            <div class="col-12">
              <div class="it-header-center-content-wrapper">
                <div class="it-brand-wrapper">
                  <NuxtLink to="/">
                    <svg class="icon" aria-hidden="true">
                      <use href="/sprites.svg#it-pa"/>
                    </svg>
                    <div class="it-brand-text">
                      <div class="it-brand-title">Comune di Bugliano</div>
                    </div>
                  </NuxtLink>
                </div>
                <div class="it-right-zone">
                  <div class="it-socials d-none d-md-flex">
                    <span>Seguici su</span>
                    <ul>
                      <li>
                        <NuxtLink to="#" aria-label="Facebook" target="_blank">
                          <svg class="icon">
                            <use href="/sprites.svg#it-facebook"/>
                          </svg>
                        </NuxtLink>
                      </li>
                      <li>
                        <NuxtLink to="#" aria-label="Github" target="_blank">
                          <svg class="icon">
                            <use href="/sprites.svg#it-github"/>
                          </svg>
                        </NuxtLink>
                      </li>
                      <li>
                        <NuxtLink to="#" aria-label="Twitter" target="_blank">
                          <svg class="icon">
                            <use href="/sprites.svg#it-twitter"/>
                          </svg>
                        </NuxtLink>
                      </li>
                    </ul>
                  </div>
                  <div class="it-search-wrapper">
                    <span class="d-none d-md-block">Cerca</span>
                    <NuxtLink to="#" class="search-link rounded-icon" aria-label="Cerca nel sito">
                      <svg class="icon">
                        <use href="/sprites.svg#it-search"/>
                      </svg>
                    </NuxtLink>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="it-header-navbar-wrapper">
        <div class="container-xxl">
          <div class="row">
            <div class="col-12">
              <!--start nav-->
              <nav class="navbar navbar-expand-lg has-megamenu" aria-label="Navigazione principale">
                <button class="custom-navbar-toggler" type="button" aria-controls="navC1" aria-label="Mostra/Nascondi la navigazione" data-bs-toggle="navbarcollapsible" data-bs-target="#navC1">
                  <svg class="icon">
                    <use href="/sprites.svg#it-burger"/>
                  </svg>
                </button>
                <div id="navC1" class="navbar-collapsable" tabindex="-1">
                  <div class="close-div">
                    <button class="btn close-menu" type="button">
                      <span class="visually-hidden">Nascondi la navigazione</span>
                      <svg class="icon">
                        <use href="/sprites.svg#it-close-big"/>
                      </svg>
                    </button>
                  </div>
                  <div class="menu-wrapper">
                    <ul class="navbar-nav">
                      <li class="nav-item" :class="{ active: isEventiActive }">
                        <NuxtLink
                          to="/eventi"
                          class="nav-link"
                          :class="{ active: isEventiActive }"
                          :aria-current="isEventiActive ? 'page' : undefined"
                        >
                          <span>Vivi Bugliano</span>
                        </NuxtLink>
                      </li>
                    </ul>
                  </div>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>