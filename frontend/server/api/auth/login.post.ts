export default defineEventHandler(async (event) => {
    const body = await readBody(event)

    const config = useRuntimeConfig()
    const response = await $fetch<{ token: string; user: unknown }>(
        `${config.laravelApiUrl}/login`,
        {
            method: 'POST',
            body: {
                email: body.email,
                password: body.password,
            },
            headers: {
                Accept: 'application/json',
            },
        }
    )

    setCookie(event, 'auth_token', response.token, {
        httpOnly: true,
        secure: !import.meta.dev,
        sameSite: 'lax',
        path: '/',
        maxAge: 60 * 60 * 24 * 7,
    })

    return { user: response.user }
})