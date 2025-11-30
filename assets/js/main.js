// Nomes brasileiros para notificações
const brazilianNames = [
  "Ana Silva",
  "Maria Santos",
  "João Oliveira",
  "Pedro Costa",
  "Juliana Lima",
  "Carlos Souza",
  "Fernanda Alves",
  "Lucas Pereira",
  "Mariana Rodrigues",
  "Rafael Ferreira",
  "Camila Martins",
  "Bruno Carvalho",
  "Beatriz Ribeiro",
  "Gabriel Almeida",
  "Larissa Gomes",
  "Amanda Rocha",
  "Thiago Barbosa",
  "Patrícia Dias",
  "Rodrigo Mendes",
  "Vanessa Castro",
]

const brazilianCities = [
  "São Paulo, SP",
  "Rio de Janeiro, RJ",
  "Belo Horizonte, MG",
  "Curitiba, PR",
  "Porto Alegre, RS",
  "Salvador, BA",
  "Brasília, DF",
  "Fortaleza, CE",
  "Recife, PE",
  "Manaus, AM",
  "Goiânia, GO",
  "Belém, PA",
  "Campinas, SP",
  "Vitória, ES",
  "Florianópolis, SC",
]

const products = ["Kit Seca Tudo 7 Dias", "Kit Dieta Cetogênica", "Detox Líquido", "Kit Prometi SECAR"]

// Função para mostrar notificação de compra
function showPurchaseNotification() {
  const notification = document.getElementById("purchase-notification")
  const nameElement = document.getElementById("notification-name")
  const messageElement = document.getElementById("notification-message")

  const randomName = brazilianNames[Math.floor(Math.random() * brazilianNames.length)]
  const randomCity = brazilianCities[Math.floor(Math.random() * brazilianCities.length)]
  const randomProduct = products[Math.floor(Math.random() * products.length)]
  const minutesAgo = Math.floor(Math.random() * 30) + 1

  nameElement.textContent = randomName
  messageElement.textContent = `de ${randomCity} comprou ${randomProduct} há ${minutesAgo} minutos`

  notification.classList.add("show")

  setTimeout(() => {
    notification.classList.remove("show")
  }, 5000)
}

// Mostrar notificação a cada 30 segundos
setInterval(showPurchaseNotification, 30000)

// Mostrar primeira notificação após 5 segundos
setTimeout(showPurchaseNotification, 5000)

// Mobile menu toggle
document.addEventListener("DOMContentLoaded", () => {
  const mobileToggle = document.querySelector(".mobile-menu-toggle")
  const mainNav = document.querySelector(".main-nav")

  if (mobileToggle) {
    mobileToggle.addEventListener("click", () => {
      mainNav.style.display = mainNav.style.display === "block" ? "none" : "block"
    })
  }

  // Smooth scroll para links internos
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  })

  function checkVisibleElements() {
    const elements = document.querySelectorAll(".product-card, .benefit-item, .testimonial-item")

    elements.forEach((element) => {
      const position = element.getBoundingClientRect()

      if (position.top < window.innerHeight && position.bottom >= 0) {
        element.style.opacity = "1"
        element.style.transform = "translateY(0)"
      }
    })
  }

  // Animação de scroll
  window.addEventListener("scroll", checkVisibleElements)

  // Inicializar animações
  const elements = document.querySelectorAll(".product-card, .benefit-item, .testimonial-item")

  elements.forEach((element) => {
    element.style.opacity = "0"
    element.style.transform = "translateY(30px)"
    element.style.transition = "all 0.6s ease"
  })

  setTimeout(checkVisibleElements, 100)
})
