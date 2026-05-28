<x-layout>
<div class="previous-layout-classes">
    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-4">

        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="page-first-title">
                {{ __('diving_log.diving_validation_request') }}
            </h1>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center">
            <a href="{{ route('individual.diving-log.index') }}" class="btn btn-info">
                {{ __('main.back') }}
            </a>
        </div>
    </div>


    <div class="mx-auto flex flex-col md:flex-row gap-4 items-start">

        <div class="w-full md:w-2/3 md:order-2">
            <x-information-box
                :title="__('diving_log.how_to_request_verification')"
                :body="__('diving_log.how_to_request_verification_body')"></x-information-box>
        </div>

        <div class="w-full md:w-1/2 lg:w-1/3 card md:order-1">

            <div id="qr-code-scanner-overlay" class="w-full"></div>

            <form method="POST" id="divingValidation" action="{{ route('individual.diving-log-validation.store', $divingLogId) }}">
                @csrf

                <div class="flex flex-col">
                    <label>{{ __('diving_log.insert_code_here') }}</label>
                    <input type="text" class="form-input w-full" name="cmas_code" id="cmas_code" placeholder="00000" value="{{ old('cmas_code') }}">
                </div>

                <div class="mt-4 items-center flex w-full">
                <button type="submit" class="btn-primary w-full">
                    {{ __('diving_log.verify_dive') }}
                </button>
                </div>

                <div class="text-center mt-4 flex flex-col">
                <label>{{ __('diving_log.or_scan_qr_code') }}</label>

                <button onclick="initializeQrCodeScanner()" type="button" class="mt-4 btn btn-outline text-gray-600 w-full">
                    <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6  fill-gray-400">
                        <path d="M16 17V16H13V13H16V15H18V17H17V19H15V21H13V18H15V17H16ZM21 21H17V19H19V17H21V21ZM3 3H11V11H3V3ZM5 5V9H9V5H5ZM13 3H21V11H13V3ZM15 5V9H19V5H15ZM3 13H11V21H3V13ZM5 15V19H9V15H5ZM18 13H21V15H18V13ZM6 6H8V8H6V6ZM6 16H8V18H6V16ZM16 6H18V8H16V6Z">
                        </path>
                    </svg>
                    <div>{{ __('diving_log.scan_qr_code') }}</div>
                    </div>
                </button>
                </div>

            </form>

        </div>



    </div>

</div>


  @push('head-css')
  <style>
    #qr-code-scanner::before {
      content: "";
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      width: calc(min(60vw, 60vh) - 10px);
      height: calc(min(60vw, 60vh) - 10px);
      margin-top: calc(-1 * ((min(60vw, 60vh) - 10px) / 2));
      margin-left: calc(-1 * ((min(60vw, 60vh) - 10px) / 2));
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 0 0 10000px rgba(0, 0, 0, 0.5);
    }

  </style>
  @endpush

  @push('footer-scripts')
  <script>
    window.initializeQrCodeScanner = function() {

      const html5QrCode = new Html5Qrcode("qr-code-scanner-overlay");
      html5QrCode.start(
        { facingMode: "environment" },  // constraints: { facingMode: "environment" } or { facingMode: "user" }
        {
          fps: 20,    // Optional, frame per seconds for qr code scanning
          qrbox: { width: 250, height: 250 }, // Optional, if you want bounded box UI
          useBarCodeDetectorIfSupported: true,
          willReadFrequently: true,
          showZoomSliderIfSupported: true,
          defaultZoomValueIfSupported: 5
        },
        (decodedText, decodedResult) => {
            // Handle the QR code result
            document.getElementById("cmas_code").value = decodedText;
            console.log(`Code matched = ${decodedText}`, decodedResult);
            // Stop scanning after a successful sca;n
            html5QrCode.stop();
            // Submit form
            document.getElementById("divingValidation").submit();
        },
        (errorMessage) => {
          // parse error, ignore it.
          console.log('error: ' + errorMessage);
        })
    }
  </script>
  @endpush

</x-layout>
