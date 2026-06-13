document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.password-toggle').forEach(icon => {
        icon.addEventListener('click', () => {
            const inputName = icon.dataset.target
            const input = document.querySelector(`input[name="${inputName}"]`)

            if (!input) return

            const isPassword = input.type === 'password'
            input.type = isPassword ? 'text' : 'password'

            icon.classList.toggle('fa-eye')
            icon.classList.toggle('fa-eye-slash')
        })
    })
})