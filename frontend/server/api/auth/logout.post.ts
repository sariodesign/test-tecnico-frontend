export default defineEventHandler(async (event) => {
    const config = useRuntimeConfig()
    const token = getCookie(event, 'auth_token')

    if (token) {
        try {
            await $fetch(`${config.laravelApiUrl}/logout`, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${token}`,
                },
            })
        } catch (error) {
            // Se fallisce la revoca del token, loggo l'errore ma procedo comunque con il logout locale
            console.warn('Revoca token lato Laravel fallita, procedo con logout locale:', error)
        }
    }

    // Evito che l'utente rimanga loggato
    deleteCookie(event, 'auth_token')

    return { success: true }
})