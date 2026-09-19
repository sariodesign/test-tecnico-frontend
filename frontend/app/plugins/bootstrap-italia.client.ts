export default defineNuxtPlugin(async () => {
    if (import.meta.client) {
        // Importa dinamicamente il file JS di Bootstrap Italia nel browser
        await import('bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js')
    }
})