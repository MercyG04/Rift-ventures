
<x-app-layout>
    <div class="max-w-3xl mx-auto py-12 px-4">
        <h1 class="text-2xl font-bold mb-6">Complete Payment</h1>

        <div class="bg-white p-6 rounded shadow">
            <p class="mb-4 text-lg">
                Amount Due: 
                <span class="font-bold text-green-600">
                    KES {{ number_format($payment->amount / 100, 2) }}
                </span>
            </p>

            <!-- Stripe Container -->
            <form id="payment-form" x-data="stripePayment()">
                <div id="payment-element" class="mb-6">
                    <!-- Stripe Elements will inject the card fields here -->
                </div>

                <!-- Error Message Container -->
                <div id="error-message" class="text-red-600 mb-4 hidden"></div>

                <button 
                    id="submit" 
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
                    :disabled="loading"
                    @click.prevent="pay()"
                >
                    <span x-show="!loading">Pay Now</span>
                    
                    <!-- Loading Spinner -->
                    <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Stripe JS SDK -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        function stripePayment() {
            return {
                loading: false,
                stripe: null,
                elements: null,

                init() {
                    // Initialize Stripe with Publishable Key
                    this.stripe = Stripe("{{ $stripeKey }}");
                    
                    const options = {
                        clientSecret: "{{ $clientSecret }}",
                        appearance: { theme: 'stripe' },
                    };
                    
                    this.elements = this.stripe.elements(options);
                    const paymentElement = this.elements.create('payment');
                    paymentElement.mount('#payment-element');
                },

                async pay() {
                    this.loading = true; // Disable button & show spinner
                    document.getElementById('error-message').classList.add('hidden');

                    const { error } = await this.stripe.confirmPayment({
                        elements: this.elements,
                        confirmParams: {
                            // Return URL where Stripe redirects after success
                            return_url: "{{ route('payment.success', $booking) }}",
                        },
                    });

                    if (error) {
                        // Show error to your customer (e.g., insufficient funds)
                        const messageContainer = document.getElementById('error-message');
                        messageContainer.textContent = error.message;
                        messageContainer.classList.remove('hidden');
                        this.loading = false; // Re-enable button
                    } else {
                        // Success! Stripe will redirect automatically.
                    }
                }
            }
        }
    </script>
</x-app-layout>