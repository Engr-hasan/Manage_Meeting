<table border="0" style="text-align: justify;">
    <tr>
        <td colspan="2">
            <p style="font-size:13px !important"><b>File no : {{ $appData->tracking_no }}</b></p>
        </td>
        <td colspan="1" style="text-align:right">
            <p>Date: {{ $appReceivedDate }}</p>
        </td>
    </tr>
    <tr><td colspan="3"><p>&nbsp;</p></td></tr>
    <tr><td colspan="3"><p>&nbsp;</p></td></tr>
    <tr>
        <td colspan="3">
            <p><span>Managing Director/C.E.O.</span>
                <br/> {{ $appData->company_name }}
                <br/> {{ $appData->infrastructure_space }}
                <br/> {{ $appData->park_name }}
                <br/> {{ $appData->park_name }} , {{ $appData->area_name }}
            </p>
        </td>
    </tr>
    <tr><td colspan="3"><p>&nbsp;</p></td></tr>
    <tr>
        <td colspan="3">
            <p>Sub: <strong>Approval for setting up a(n) {{ $appData->type_of_business_service }}  in  {{ $appData->park_name }}</strong></p>
        </td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">Dear M.D. / C.E.O,</td></tr>
    <tr>
        <td colspan="3">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;With reference to your Project Proposal received on  !!!Appp Received Data,
                this is to inform you that the Authority is pleased to issue this clearance subject to approval from competent
                authorities and compliance of all legal requirements of  your project for setting up a(n)
                <strong>{{ $appData->type_of_business_service }}</strong> in
                <strong>{{ $appData->park_name }}, {{ $appData->area_name }}</strong>
                on terms and conditions as indicated below:-</p>
        </td>
    </tr>
    <tr><td colspan="3"><p>&nbsp;</p></td></tr>
    <tr>
        <td style="width: 200px !important; vertical-align: text-top;"><p>01. Name of the project</p></td>
        <td  colspan="2">
            <table><tr><td style="vertical-align: text-top;">:</td><td><strong>{{ $appData->company_name }}</strong></td></tr></table>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: text-top;"><p>02. Products</p></td>
        <td  colspan="2">
            <table>
                <tr><td style="vertical-align: text-top;">:</td><td>{{ $appData->sp_product_description }}</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: text-top;"><p>03. Cost of the project</p></td>
        <td  colspan="2">
            <table>
                <tr>
                    <td style="vertical-align: text-top;">:</td>
                    <td>US$ {{ (!empty($appData->sp_project_cost)?number_format($appData->sp_project_cost,2):'0:00') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="vertical-align: text-top;"><p>04. Type of Investment</p></td>
        <td  colspan="2">
            <table>
                <tr>
                    <td style="vertical-align: text-top;">:</td>
                    <td>98.08 % Export Oriented?? <br>
                        1.92 % Domestic Market??
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: text-top;"><p>05. Type of Industry</p></td>
        <td  colspan="2">
            <table>
                <tr><td style="vertical-align: text-top;">:</td><td>{{ $appData->industry_type_name }}</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td  style="vertical-align: text-top;"><p>06. Annual production capacity</p></td>
        <td  colspan="2">
            <table>
                <tr><td style="vertical-align: text-top;">:</td><td>Name of the App Cant 5kg</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td  style="vertical-align: text-top;"><p>07. Employment</p></td>
        <td  colspan="2">
            <table>
                <tr><td style="vertical-align: text-top;">:</td>
                    <td> {{ $addData->total_employee }} persons including {{ $appData->foreign_employee }} foreign nationals  </td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><p style="vertical-align: text-top;">08. Status of the company</p></td>
        <td  colspan="2">
            <table>
                <tr><td style="vertical-align: text-top;">:</td><td>{{ $appData->organization_type }}</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: text-top;"><p>09. Manufacturing Process</p></td>
        <td  colspan="2">
            <table>
                <tr><td style="vertical-align: text-top;">:</td>
                    <td>{{ $appData->sp_manufacture_process }}</td></tr>
            </table>
        </td>
    </tr>
    {{--<tr>--}}
        {{--<td  style="vertical-align: text-top;"><p>10.  Area of land/SFB to be allotted</p></td>--}}
        {{--<td  colspan="2">--}}
            {{--<table>--}}
                {{--<tr><td style="vertical-align: text-top;">:</td>--}}
                    {{--<td>  45 M<sup>2</sup> plot in--}}
                        {{--Plot Address</td></tr>--}}
            {{--</table>--}}
        {{--</td>--}}
    {{--</tr>--}}
    <tr><td></td><tr/>
    {{--<tr>--}}
        {{--<td colspan="3">--}}
            {{--<p> 10. This Space Allocation Certificate is only for the construction of {{ $appData->company_name }} factory/project.--}}
                {{--Any expansion or modification of this project will require obtaining further permission from the authority.--}}
                {{--Machineries and equipments required to be imported for the project valued approximately--}}
                {{--at US$ 561.00 only on the terms and conditions acceptable to this Authority. </p></td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td colspan="3"><p  >--}}
                {{--12. Machineries to be imported for the project (as per list enclosed with the Project Proposal) shall be !!! Material state.--}}
                {{--Prior approval of the Authority shall be required for the import/shipment of machinery from abroad.</p></td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td colspan="3">--}}
            {{--<p>13. Before procurement of machineries and building-materials from abroad you are required to submit--}}
                {{--3 (three) sets of price quotations from reputed machinery suppliers or in case of procurement of machinery from manufacturer,--}}
                {{--1 (one) set of price quotation along with catalogues etc. of the machinery for approval of the Authority.</p></td>--}}
    {{--</tr>--}}
    <tr>
        <td colspan="3">
            <table  border="0" style="text-align: justify;">
                <tr>
                    <td colspan="3">
                        10. The company will have to:
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">a.</td>
                    <td>maintain the ratio of export and domestic sale within 98.08 : 01.92
                        of  entire product of its factory;</td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">b.</td>
                    <td>submit monthly reports to the Authority on the progress of implementation of the project;</td>
                </tr>
            </table>

            <table  border="0" style="text-align: justify;">
                <tr>
                    <td style="vertical-align:top;">c.</td>
                    <td>commission the project in the allotted land space within a period of twelve months failing  which the Authority
                        may revoke  this permission;
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">d.</td>
                    <td>furnish such other data on the project to this Authority and to any other agency as may be required;</td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">e.</td>
                    <td>
                        obtain work permits / permission of the Authority prior to entry for any foreign nationals on employment in the project;
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">f.</td>
                    <td>
                        submit to this Authority, the audited Financial Statement for every financial  year within 4(four)  months from the
                        closure of each financial year of the company;
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">g.</td>
                    <td>
                        comply with the provisions of minimum wages of the workers as declared by the government from time to time
                        and the EPZ Workers Welfare Association and Industrial Relations Act, 2010 (Act no-43 of 2010) (as amended
                        from time to time by the  Authority) in regards to wages, employment, salary, leave, discipline, health,
                        compensation, insurance and other benefits to the employees engaged for work in your enterprise;
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">h.</td>
                    <td>
                        comply with the rules and regulation pertaining to environment conservation, pollution control and
                        effluent treatment and take necessary safety measures against possible fire hazards.
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">i.</td>
                    <td>
                        comply with all laws, by-laws, rules, regulations, directives of the government and of  this  Authority which are in
                        force or which may be issued from time to time in future;
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">j.</td>
                    <td>
                        comply with provisions of the “Principles and Procedures Governing Setting  up of Industries in EZ”;
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">k.</td>
                    <td>
                        obtain prior approval of the Authority in case the company decides to appoint a Managing  Agent  or transfer the
                        shares of the company;
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">l.</td>
                    <td>
                        the company shall have to submit actual Investment, export, domestic sales, employment information to the
                        Authority and/or with other government agency(s) in quarterly basis.
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">m.</td>
                    <td>
                        A full-fledged institutional setup for Environment, Health & Safety (EHS) and Corporate Social
                        Responsibility (CSR) must be put in place before operation of the project.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3"> <p>11. Depending on the category of the project/industry, the entrepreneur will be required to submit
                comprehensive Environmental impact Assessment Report and Environmental Management Plans to appropriate
                authority as well as BHTP which are to be duly approved as per the requirement of Environmental Regulations
                of the country before commencement of construction work of the project.</p></td>
    </tr>
    <tr>
        <td colspan="3"><p>12.
                The company shall bear the cost of Services and Regulatory permit fees as prescribed by the Authority from time to time</p>
        </td>
    </tr>
    <tr><td colspan="3"><p>13. No waste/old materials will be allowed to import as raw materials.</p></td></tr>
    <tr>
        <td colspan="3">
            <p>14. In the event of your failure to set up the unit within the stipulated time or infringement of any rules and regulations
                or violation of any of the above terms and conditions, the Authority may revoke the permission. </p>
        </td>
    </tr>
    <tr><td colspan="3"><p>15.  This permission is not transferable.</p></td></tr>
    <tr><td colspan="3">
            <p>16.  This permission is valid until expiration of the License awarded by Bangladesh High-Tech Park Authority (BHTPA) to
                {{ $appData->park_name }}.</p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <p>17.  If you agree with the terms and conditions contained in this letter along with those contained in the BHTPA Act, Rules,
                Policies, Guidelines, SROs, Circulars, Office orders etc. you are requested to proceed for signing the land lease agreement
                with the Developer/ Operator of {{ $appData->park_name }} with intimation to the Authority.
                Thanking and assuring you of our best co-operation at all times.
            </p>
        </td>
    </tr>
    <tr><td colspan="3"><p>&nbsp;</p></td></tr>
    <tr>
        <td colspan="2">
            <p>
                {{--<img src="{{ $qrCode }}" width="100" height="100" alt="QR code" />--}}
            </p>
            <p style="text-align:left;">Copy for Information to-<br/>
                a) Managing Director, {{ $appData->park_name }},……<br/>
                b) Commissioner, Customs Bond,………<br/>
                c) …………………………………………………<br/>
                d) …………………………………………………<br/>
                e) …………………………………………………</p>
        </td>
        <td colspan="1" style="text-align: center; vertical-align:top;">
            <p>Yours faithfully,&nbsp;&nbsp;&nbsp;</p><br/>

{{--            <img src="{{ $signature }}" alt="(signature)" width="100px"/>--}}
            <br/>
            {{ $approver }}<br/>
            Joint Secretary<br/>
            Secretary, OCPL OSS Framework Executive Board<br/>
            OCPL OSS Framework
        </td>
    </tr>
</table>