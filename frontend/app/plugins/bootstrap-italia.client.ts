export default defineNuxtPlugin(async () => {
    if (import.meta.client) {
        const bootstrapItalia = await import('bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js')
        bootstrapItalia.loadFonts?.('/fonts/')
    }
})