export interface Event {
    id: number
    title: string
    slug: string
    category: string
    description: string
    location: string
    starts_at: string
    ends_at: string
    is_published: boolean
    is_favorite?: boolean
    created_at: string
    updated_at: string
}