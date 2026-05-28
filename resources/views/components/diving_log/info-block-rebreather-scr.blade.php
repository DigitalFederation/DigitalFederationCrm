<section class="bg-white shadow-md rounded-lg p-6">
  <h2 class="text-xl text-gray-800 mb-4">Rebreather SCR</h2>

  <div class="flex flex-col gap-y-4">

      <!-- Duration Minutes -->
      <div class="dive-log-info-wrapper">
          <div class="w-fit">
              <p class="text-admin_blue text-xl font-bold">{{$divingLog->runtime}} min</p>
              <p class="text-gray-600 text-sm md:text-md">Dive Time</p>
          </div>
          <div class="w-fit">
              <!-- Stopwatch svg icon -->
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z"/>
                <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z"/>
              </svg>
          </div>
      </div>

        <!-- Duration Minutes -->
        <div class="dive-log-info-wrapper">
            <div class="w-fit">
                <p class="text-admin_blue text-xl font-bold">{{$divingLog->total_runtime}} min</p>
                <p class="text-gray-600 text-sm md:text-md">Dive Time</p>
            </div>
            <div class="w-fit">
                <!-- Stopwatch svg icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="fill-cmas_blue h-8 w-8" viewBox="0 0 16 16">
                    <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z"/>
                    <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z"/>
                </svg>
            </div>
        </div>

  </div>

</section>

<section class="card mt-4" x-data="{ open: false }">

  <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
    <h2 class="text-xl text-gray-800" >Rebreather SCR Details</h2>

    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
        <h2 class="text-xl text-gray-800">Extended Range Details</h2>

  <div class="mt-4" x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
    <table class="w-full rounded-lg">
      <tbody>
        <tr class="border-2 border-blue-100 ">
          <td class="p-2 text-gray-600  text-sm md:text-md ">SCR Total Deco Time</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
            {{ $divingLog->scr_total_deco_time }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md ">Weight</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->weight }} {{ $divingLog->weight_unit }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md ">Bailout SAC</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->bailout_sac }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Deco SAC</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->deco_sac }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Tank Volume</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->tank_volume }} {{ $divingLog->tank_volume_unit }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Oxygen</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->oxygen_percentage }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Setpoint</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->setpoint }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Start Pressure</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->start_pressure }} {{ $divingLog->start_pressure_unit }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">End Pressure</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->end_pressure }} {{ $divingLog->end_pressure_unit }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas Tank Volume</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->deco_tank_volume }} {{ $divingLog->deco_tank_volume_unit }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas Oxygen</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->deco_oxygen_percentage }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas Setpoint</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->deco_setpoint }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas Start Pressure</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->deco_start_pressure }} {{ $divingLog->deco_start_pressure_unit }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas End Pressure</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->deco_end_pressure }} {{ $divingLog->deco_end_pressure_unit }}
          </td>
        </tr>
         <!-- Equipment -->
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Suit</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_suit }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Mask</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_mask }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Fins</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_fins }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Wing</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_bcd_wing_sidemount }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Dive Computer</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_dive_computer }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Lights</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_lights }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Other</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_other }}
          </td>
        </tr>
        <tr class="border-2 border-blue-100">
          <td class="p-2 text-gray-600  text-sm md:text-md">Equipment Weight</td>
          <td class="p-2 text-admin_blue text-sm md:text-md text-left">
              {{ $divingLog->equipment_weight }} {{ $divingLog->equipment_weight_unit }}
          </td>
        </tr>

      </tbody>
    </table>
  </div>

    <div class="mt-4" x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
        <table class="w-full rounded-lg">
            <tbody>
            <tr class="border-2 border-blue-100 ">
                <td class="p-2 text-gray-600  text-sm md:text-md ">Total Deco Time</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->total_deco_time }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md ">Configuration</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->confirguration }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md ">Bottom SAC</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->sac_bottom_sac }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">SAC</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->sac_sac }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco SAC</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->sac_deco_sac }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">S.I Before</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->details_si_before }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">GF Set</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->details_gf_set }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Gradient Factor END</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->details_gradient_factor_end }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">CNS Start</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->details_cns_start }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">CNS End</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->details_cns_end }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">OTU Start</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->details_otu_start }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">OTU End</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->details_otu_end }}
                </td>
            </tr>
            <!-- Back Gas -->
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Back Gas - Tank Volume</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->back_gas_tank_volume }} @if(!empty($divingLog->back_gas_tank_volume))
                        {{ $divingLog->back_gas_tank_volume_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Back Gas - Start Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->back_gas_start_pressure }} @if(!empty($divingLog->back_gas_start_pressure))
                        {{ $divingLog->back_gas_start_pressure_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Back Gas - End Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->back_gas_end_pressure }} @if(!empty($divingLog->back_gas_end_pressure))
                        {{ $divingLog->back_gas_end_pressure_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Back Gas - Tank Type</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->back_gas_tank_type }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Back Gas - Tank Oxygen</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->back_gas_oxygen_percentage }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Back Gas - Tank Helium</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->back_gas_helium_percentage }}
                </td>
            </tr>
            <!-- Deco Gas -->
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 1 - Tank Volume</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_2_tank_volume }} @if(!empty($divingLog->deco_gas_2_tank_volume))
                        {{ $divingLog->deco_gas_2_tank_volume_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 1 - Start Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_2_start_pressure }} @if(!empty($divingLog->deco_gas_2_start_pressure))
                        {{ $divingLog->deco_gas_2_start_pressure_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 1 - End Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_2_end_pressure }} @if(!empty($divingLog->deco_gas_2_end_pressure))
                        {{ $divingLog->deco_gas_2_end_pressure_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 1 - Tank Type</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_2_tank_type }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 1 - Tank Oxygen</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_2_oxygen_percentage }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 1 - Tank Helium</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_2_helium_percentage }}
                </td>
            </tr>
            <!-- Deco Gas 3-->
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 2 - Tank Volume</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_3_tank_volume }} @if(!empty($divingLog->deco_gas_3_tank_volume))
                        {{ $divingLog->deco_gas_3_tank_volume_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 2 - Start Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_3_start_pressure }} {{ $divingLog->deco_gas_3_start_pressure_unit }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 2 - End Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_3_end_pressure }} @if(!empty($divingLog->deco_gas_3_end_pressure))
                        {{ $divingLog->deco_gas_3_end_pressure_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 2 - Tank Type</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_3_tank_type }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 2 - Tank Oxygen</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_3_oxygen_percentage }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 2 - Tank Helium</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_3_helium_percentage }}
                </td>
            </tr>
            <!-- Deco Gas 4-->
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 3 - Tank Volume</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_4_tank_volume }} @if(!empty($divingLog->deco_gas_4_tank_volume))
                        {{ $divingLog->deco_gas_4_tank_volume_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 3 - Start Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_4_start_pressure }} @if(!empty($divingLog->deco_gas_4_start_pressure))
                        {{ $divingLog->deco_gas_4_start_pressure_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 3 - End Pressure</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_4_end_pressure }} @if(!empty($divingLog->deco_gas_4_end_pressure))
                        {{ $divingLog->deco_gas_4_end_pressure_unit }}
                    @endif
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 3 - Tank Type</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_4_tank_type }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 3 - Tank Oxygen</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_4_oxygen_percentage }}
                </td>
            </tr>
            <tr class="border-2 border-blue-100">
                <td class="p-2 text-gray-600  text-sm md:text-md">Deco Gas 3 - Tank Helium</td>
                <td class="p-2 text-admin_blue text-sm md:text-md text-left">
                    {{ $divingLog->deco_gas_4_helium_percentage }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</section>
