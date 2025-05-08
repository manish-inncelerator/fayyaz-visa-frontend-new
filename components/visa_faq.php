<?php
// Fayyaz Travels - Frequently Asked Questions
$faqItems = [
    [
        'id' => 'faq1',
        'question' => 'How can I apply for an international visa from the Singapore?',
        'answer' => 'You can apply for an international visa from the Singapore by visiting the respective country\'s visa application center or applying online with us. Ensure you have all required documents, submit the application, and follow the process as per the country\'s guidelines. Fayyaz Travels simplifies this process by handling your application efficiently.'
    ],
    [
        'id' => 'faq2',
        'question' => 'Is it possible to apply for multiple visas at the same time?',
        'answer' => 'Yes, you can apply for multiple visas for different countries simultaneously. However, each country has its own rules and requirements. Fayyaz Travels assists in managing multiple visa applications, ensuring compliance with all necessary procedures.'
    ],
    [
        'id' => 'faq3',
        'question' => 'What documents are required for a tourist visa?',
        'answer' => 'Commonly required documents include a valid passport, recent passport-sized photos, proof of accommodation, return tickets, travel insurance, financial proof, and sometimes an invitation letter. Requirements may differ depending on the destination.'
    ],
    [
        'id' => 'faq4',
        'question' => 'How can I demonstrate strong ties to my home country in my visa application?',
        'answer' => 'To prove strong ties, you can provide employment verification (job contract, leave approval), property ownership documents, family relationship proof, bank statements, and tax records. These help assure authorities that you plan to return after your visit.'
    ],
    [
        'id' => 'faq5',
        'question' => 'Will I need to attend an interview for my tourist visa?',
        'answer' => 'Interview requirements depend on the country and visa type. Some countries, such as the USA, require interviews for most applicants, while others may not. Fayyaz Travels provides guidance on whether an interview is necessary for your application.'
    ],
    [
        'id' => 'faq6',
        'question' => 'What should I do if my visa application is denied?',
        'answer' => 'If your application is rejected, review the rejection reasons, address the issues, and gather additional supporting documents. Fayyaz Travels can assess your previous application and guide you through a stronger reapplication process.'
    ],
    [
        'id' => 'faq7',
        'question' => 'Can I extend my tourist visa while abroad?',
        'answer' => 'To extend a tourist visa while traveling, contact the immigration office of the country you are in before your visa expires. You may need to submit an application, justify the extension, and provide proof of funds and valid insurance. Approval is subject to the country\'s regulations.'
    ],
    [
        'id' => 'faq8',
        'question' => 'How can I avoid mistakes on my visa application form?',
        'answer' => 'To prevent errors, read all instructions carefully, prepare required details in advance, use capital letters where needed, ensure consistency with passport details, and double-check before submitting. Fayyaz Travels provides professional form-filling assistance for accuracy.'
    ],
    [
        'id' => 'faq9',
        'question' => 'When should I apply for a tourist visa?',
        'answer' => 'It is recommended to apply 2-3 months before your intended travel date. Processing times vary, especially during peak seasons. Some countries allow applications up to six months in advance, while others have shorter timeframes. Applying early helps avoid last-minute complications.'
    ],
    [
        'id' => 'faq10',
        'question' => 'Is travel insurance necessary for a tourist visa?',
        'answer' => 'Some countries, like Schengen states, require travel insurance with a minimum coverage of €30,000. Others may strongly recommend it. Even when not mandatory, travel insurance is advisable for medical emergencies, trip cancellations, and lost luggage protection.'
    ],
    [
        'id' => 'faq11',
        'question' => 'What if I realize there is a mistake in my visa application after submission?',
        'answer' => 'If you find an error after submission, contact the embassy or visa center immediately. Minor mistakes may be corrected with a letter, while major errors might require withdrawal and reapplication. Fayyaz Travels can guide you on the best course of action.'
    ]
];

?>

<div class="col-lg-10">
    <?php foreach ($faqItems as $item): ?>
        <div class="faq-item">
            <button class="faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $item['id']; ?>" aria-expanded="false">
                <?php echo $item['question']; ?>
                <i class="bi bi-plus-circle"></i>
            </button>
            <div class="collapse" id="<?php echo $item['id']; ?>">
                <div class="faq-answer">
                    <?php echo $item['answer']; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>