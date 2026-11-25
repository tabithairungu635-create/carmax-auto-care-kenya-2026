const menuToggle = document.getElementById('menuToggle');
const navigation = document.getElementById('navigation');

if (menuToggle && navigation) {
    menuToggle.addEventListener('click', () => {
        const isOpen = navigation.classList.toggle('open');

        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.innerHTML = isOpen
            ? '<i class="fa-solid fa-xmark"></i>'
            : '<i class="fa-solid fa-bars"></i>';
    });

    navigation.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navigation.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
        });
    });
}

const enquiryForm = document.getElementById('enquiryForm');

if (enquiryForm) {
    enquiryForm.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!this.reportValidity()) return;

        const name = document.getElementById('customerName').value.trim();
        const phone = document.getElementById('customerPhone').value.trim();
        const message = document.getElementById('customerMessage').value.trim();

        const text = [
            'Hello CARMAX AUTO CARE,',
            '',
            'I would like to make an enquiry.',
            '',
            `Name: ${name}`,
            `Phone: ${phone}`,
            `Enquiry: ${message}`
        ].join('\n');

        const whatsappURL =
            'https://wa.me/254741637219?text=' + encodeURIComponent(text);

        window.open(whatsappURL, '_blank', 'noopener,noreferrer');
    });
}
