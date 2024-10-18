document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("earlyAccessForm");
    
    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission
        submitToMailchimp(); // Call the function to handle Mailchimp submission
    });
});

async function submitToMailchimp() {
    const email = document.getElementById('email').value;
    const listId = 'acffebec23'; // Replace with your Mailchimp list ID
    const dataCenter = 'us22';   // Replace with your Mailchimp data center prefix (e.g., us5)
    const apiKey = '5e098748b357bb73e7c28201caf4e6b5-us22';

    // Hash the email to match Mailchimp's lookup requirements
    const emailHash = md5(email.toLowerCase());

    try {
        // Check if the email already exists
        const checkResponse = await fetch(`https://${dataCenter}.api.mailchimp.com/3.0/lists/${listId}/members/${emailHash}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${apiKey}`,
                'Content-Type': 'application/json'
            }
        });

        if (checkResponse.ok) {
            // Email exists; you could display a message if needed
            document.getElementById('thankYouMessage').style.display = 'block';
        } else {
            // Email not found; add it to the list
            const subscribeResponse = await fetch(`https://${dataCenter}.api.mailchimp.com/3.0/lists/${listId}/members`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiKey}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email_address: email, status: 'subscribed' })
            });

            if (subscribeResponse.ok) {
                document.getElementById('thankYouMessage').style.display = 'block';
            } else {
                console.error('Failed to subscribe:', await subscribeResponse.json());
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
