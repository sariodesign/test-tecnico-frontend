<script lang="ts" setup>
const route = useRoute()
const { user, isLoggedIn, logout } = useAuth()
</script>

<template>
  <header>
    
  </header>
    <div class="it-header-slim-wrapper">
      <div class="container-xxl">
        <div class="row">
          <div class="col-12">
            <div class="it-header-slim-wrapper-content">
              <a class="d-lg-block navbar-brand" href="#">Ente appartenenza</a>
              <div class="it-header-slim-right-zone">
                <div class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Selezione lingua: lingua selezionata</span>
                    <span>ITA</span>
                    <svg class="icon d-none d-lg-block"><use href="/sprites.svg#it-expand"></use></svg>
                  </a>
                  <div class="dropdown-menu">
                    <div class="row">
                      <div class="col-12">
                        <div class="link-list-wrapper">
                          <ul class="link-list">
                            <li><a class="dropdown-item list-item" href="#"><span>ITA <span class="visually-hidden">selezionata</span></span></a></li>
                            <li><a class="dropdown-item list-item" href="#"><span>ENG</span></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <NuxtLink class="btn btn-primary btn-icon btn-full" to="/accedi" v-if="!isLoggedIn">
                  <span class="rounded-icon">
                    <svg class="icon icon-primary">
                      <use href="/sprites.svg#it-user"></use>
                    </svg>
                  </span>
                  <span class="d-none d-lg-block">Accedi all'area personale</span>
                </NuxtLink>
                <template v-else>
                  <NuxtLink class="btn btn-primary btn-icon btn-full" to="/area-personale">
                    <span class="rounded-icon">
                      <svg class="icon icon-primary">
                        <use href="/sprites.svg#it-user"></use>
                      </svg>
                    </span>
                    <span class="d-none d-lg-block">Area personale di {{ user?.name }}</span>
                  </NuxtLink>
                  <button @click="logout" class="btn btn-primary btn-icon">
                    <svg class="icon icon-white">
                      <use href="/sprites.svg#it-logout"></use>
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
    <div class="it-header-center-wrapper">
      <div class="container-xxl">
        <div class="row">
          <div class="col-12">
            <div class="it-header-center-content-wrapper">
              <div class="it-brand-wrapper">
                <NuxtLink to="/">
                  <svg class="icon" aria-hidden="true">
                    <use href="/sprites.svg#it-pa"></use>
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
                          <use href="/sprites.svg#it-facebook"></use>
                        </svg>
                      </NuxtLink>
                    </li>
                    <li>
                      <NuxtLink to="#" aria-label="Github" target="_blank">
                        <svg class="icon">
                          <use href="/sprites.svg#it-github"></use>
                        </svg>
                      </NuxtLink>
                    </li>
                    <li>
                      <NuxtLink to="#" aria-label="Twitter" target="_blank">
                        <svg class="icon">
                          <use href="/sprites.svg#it-twitter"></use>
                        </svg>
                      </NuxtLink>
                    </li>
                  </ul>
                </div>
                <div class="it-search-wrapper">
                  <span class="d-none d-md-block">Cerca</span>
                  <NuxtLink to="#" class="search-link rounded-icon" aria-label="Cerca nel sito">
                    <svg class="icon">
                      <use href="/sprites.svg#it-search"></use>
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
                  <use href="/sprites.svg#it-burger"></use>
                </svg>
              </button>
              <div class="navbar-collapsable" id="navC1" tabindex="-1">
                <div class="close-div">
                  <button class="btn close-menu" type="button">
                    <span class="visually-hidden">Nascondi la navigazione</span>
                    <svg class="icon">
                      <use href="/sprites.svg#it-close-big"></use>
                    </svg>
                  </button>
                </div>
                <div class="menu-wrapper">
                  <ul class="navbar-nav">
                    <li class="nav-item active">
                      <NuxtLink
                        to="/eventi"
                        class="nav-link"
                        active-class="active"
                        :aria-current="route.path.startsWith('/eventi') ? 'page' : undefined"
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
</template>