export interface InputField {
    label: string
    id: string
    type?: 'text' | 'email' | 'password'
    placeholder?: string
    disabled?: boolean
    readonly?: boolean
    required?: boolean
    error?: string
}