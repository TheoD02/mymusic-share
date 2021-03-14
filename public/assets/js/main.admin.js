/** Gestion des champs du formulaire de catégorie (slug) */
let categoryForm = document.querySelector('#categoryForm');

if (categoryForm !== null) {
    let categoryName = categoryForm.querySelector('#name');
    let categorySlug = categoryForm.querySelector('#slug');

    /**
     * Remplace les espace/multiples espaces par des tiret
     */
    categoryName.addEventListener('keyup', (e) =>
    {
        let categoryNameValue = e.currentTarget.value;
        categorySlug.value = slugify(categoryNameValue);
    });

    /**
     * Remplace les caractère en majuscule par des minuscule
     */
    categorySlug.addEventListener('keyup', (e) =>
    {
        e.currentTarget.value = e.currentTarget.value.toLowerCase();
    });

    /** Remplace les accents par des caractère sans accent, et les espace par des tiret */
    function slugify(str)
    {
        let map = {
            '-': ' ',
            'a': 'á|à|ã|â|À|Á|Ã|Â',
            'e': 'é|è|ê|É|È|Ê',
            'i': 'í|ì|î|Í|Ì|Î',
            'o': 'ó|ò|ô|õ|Ó|Ò|Ô|Õ',
            'u': 'ú|ù|û|ü|Ú|Ù|Û|Ü',
            'c': 'ç|Ç',
            'n': 'ñ|Ñ',
        };
        for (let pattern in map) {
            str = str.replace(new RegExp(map[pattern], 'g'), pattern).toLowerCase();
        }
        return str;
    }
}