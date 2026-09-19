export function formatDate(
    value: string | null | undefined,
    options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' }
) {
    if (!value) return ''
    return new Intl.DateTimeFormat('it-IT', options).format(new Date(value))
}