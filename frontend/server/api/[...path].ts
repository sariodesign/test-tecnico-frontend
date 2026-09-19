export default defineEventHandler(async (event): Promise<unknown> => {
    const config = useRuntimeConfig()

    const path = event.context.params?.path ?? ''

    const token = getCookie(event, 'auth_token')

    const headers: Record<string, string> = {
        Accept: 'application/json',
    }
    if (token) {
        headers.Authorization = `Bearer ${token}`
    }

    const method = event.method
    const response = await $fetch.raw(`${config.laravelApiUrl}/${path}`, {
        method,
        headers,
        query: getQuery(event),
        body: method === 'GET' || method === 'HEAD'
            ? undefined
            : await readBody(event).catch(() => undefined),
    })

    return response._data
})