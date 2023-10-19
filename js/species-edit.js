$(function(){

	console.log('species-edit.js');

    const id = $("#spId").val();
    findOne(id);
    function findOne(id){
        $.ajax({
            url: "../back-end/api/species/findOne.php?id="+id,
            method: "GET",
            beforeSend: function () {
                $(".preloader").show();
            },
            complete: function () {
                $(".preloader").hide();                
            },
            success: function (res) {
                console.log({'findOne':res});
                if(res.status === '200'){
                    let result = res.data[0];
                    $("#groupId").val(result.groupId)
                    $("#spKingdom").val(result.spKingdom);
                    $("#spPhylum").val(result.spPhylum);
                    $("#spClass").val(result.spClass);
                    $("#spOrder").val(result.spOrder);
                    $("#spFamily").val(result.spFamily);
                    $("#spSubFamily").val(result.spSubFamily);
                    $("#spGenus").val(result.spGenus);
                    $("#spGenusAuth").val(result.spGenusAuth);
                    $("#spSpecies").val(result.spSpecies);
                    $("#spSpeciesAuth").val(result.spSpeciesAuth);
                    $("#spSubSpecies").val(result.spSubSpecies);
                    $("#spSubSpeciesAuth").val(result.spSubSpeciesAuth);
                    $("#spScName").val(result.spScName);
                    $("#spScNameAuth").val(result.spScNameAuth);
                    $("#spEngName").val(result.spEngName);
                    $("#spLocalName").val(result.spLocalName);
                    $("#spHabitat").val(result.spHabitat);
                    
                    $("#spIucnBd").html(result.spIucnBd);
                    $("#spIucnBdYear").val(result.spIucnBdYear);
                    $("#spIucnGb").html(result.spIucnGb);
                    $("#spIucnGbYear").val(result.spIucnGbYear);
                    $("#spCitis").val(result.spCitis);

                    $("#spShortDes").html(result.spShortDes);
                    $("#spBiology").html(result.spBiology);
                    $("#spCitePage").html(result.spCitePage);
                    
                    if(result.spSeq.length > 0){
                        let sequences = result.spSeq;
                        let sequence = '';
                        sequences.forEach(item => {
                            sequence += `<div class="refItem d-flex align-items-center mb-2">
                            <div class="summerNoteLink form-control">${item}</div><a href="#" id="removeRefBtn" class="chipsBtn hide ml-2"> <i data-feather="x"></i> </a></div>`
                        });
                        $("#sequences-area").html(sequence);
                    }

                    if(result.spOc.length > 0){
                        let occurances = result.spOc;
                        let occurance = '';
                        occurances.forEach(item => {
                            occurance += `<div class="refItem d-flex align-items-center mb-2">
                            <div class="summerNoteLink form-control">${item}</div><a href="#" id="removeRefBtn" class="chipsBtn hide ml-2"> <i data-feather="x"></i> </a></div>`
                        });
                        $("#occurances-area").html(occurance);
                    }

                    if(result.spAcName.length > 0){
                        let acceptedNames = result.spAcName;
                        let acceptedName = '';
                        acceptedNames.forEach(item => {
                            acceptedName += `<div class="refItem d-flex align-items-center mb-2">
                            <div class="summerNoteLink form-control">${item}</div><a href="#" id="removeRefBtn" class="chipsBtn hide ml-2"> <i data-feather="x"></i> </a></div>`
                        });
                        $("#acceptedNames-area").html(acceptedName);
                    }

                    if(result.spPhotos.length){
                        let photo = '';
                        result.spPhotos.forEach((item) => {
                            photo += `<span><i class="chipsBtn hide"><i data-feather="x"></i></i><img dataId="${item.id}" src="../uploads/${item.name}" alt="${item.name}" title="${item.name}"/></span>`;
                        });
                        $("#featureImages").html(photo);
                    }else{
                        $("#featureImages").html(``);
                    }

                    if(result.spRef.length > 0){
                        let references = result.spRef;
                        let refrence = '';
                        references.forEach(item => {
                            refrence += `<div class="refItem d-flex align-items-center mb-2">
                            <div class="summerNoteLink form-control">${item}</div><a href="#" id="removeRefBtn" class="chipsBtn hide ml-2"> <i data-feather="x"></i> </a></div>`
                        });
                        $("#references-area").html(refrence);
                    }

                    feather.replace();
                }
            },
            error: function (error) {
                console.log(error.responseText);
            }
        });
    }    
    
    $(document).on("click", "#editSpecies", function(e){
        e.preventDefault();
        $(".saveBtn").html(`<a href="#" id="cancelEdit" class="btn btn-soft-primary mr-2">Cancel</a> <a href="#" id="saveSpecies" class="btn btn-primary mr-2">Save</a>`);
        $("#speciesInfo .form-control").removeAttr('readonly');
        $("#addRef").html(`<a href="#" id="additionBtn" wrapper="references-area"> <i data-feather="plus"></i> Add Reference</a>`);    
        $("#addSeq").html(`<a href="#" id="additionBtn" wrapper="sequences-area"> <i data-feather="plus"></i> Add Sequence</a>`);    
        $("#addOcu").html(`<a href="#" id="additionBtn" wrapper="occurances-area"> <i data-feather="plus"></i> Add Occurance</a>`);    
        $("#addAc").html(`<a href="#" id="additionBtn" wrapper="acceptedNames-area"> <i data-feather="plus"></i> Add Accepted Name</a>`);    
        $("#addPhotos").html(`<a href="#" wrapper="featureImages" id="selectImage" type="multiple"> <i data-feather="plus"></i> Add Photo</a>`);   
        $(".chipsBtn").removeClass('hide');
        $(".summerNoteLink").summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['link']]
              ],
              disableResizeEditor: true
        });
        $('.note-statusbar').hide();
        feather.replace();
        return false;
    })

    $(document).on('click', '.refItem .chipsBtn', function(e){
        e.preventDefault()
        $(this).parents('.refItem').remove()
    })
    
    $(document).on("click", "#cancelEdit", function(e){
        e.preventDefault();
        $(".saveBtn").html(``);
        $("#speciesInfo .form-control").attr('readonly', true);
        $("#addRef").html(``);    
        $("#addSeq").html(``);    
        $("#addOcu").html(``);    
        $("#addAc").html(``);    
        $("#addPhotos").html(``);       
        $(".chipsBtn").addClass('hide');
        $('.summerNoteLink').each(function( index ) {
            $(this).summernote('destroy');
        });
        findOne(id);
    })

    $(document).on("click", "#additionBtn", function(e){
        e.preventDefault();
        const wrapper = $(this).attr('wrapper')
        $("#"+wrapper).append(`<div class="refItem d-flex align-items-center mb-2">
        <div class="summerNoteLink form-control"></div>
        <a href="#" class="chipsBtn ml-2"> <i data-feather="x"></i> </a></div>`);
        feather.replace();
        $(".summerNoteLink").summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['link']]
              ],
              disableResizeEditor: true
        });
        $('.note-statusbar').hide();
        return false;
    })
    

    $(document).on("click", "#removeRefBtn", function(e){
        e.preventDefault();
        $(this).parents('.refItem').remove();
    })
    
    $(document).on("click", "#saveSpecies", function(e){
        e.preventDefault();
        let id = $("#spId").val()
        
        let data = {}
        data.groupId            = $("#groupId").val()
        data.spKingdom          = $("#spKingdom").val()
        data.spPhylum           = $("#spPhylum").val()
        data.spClass            = $("#spClass").val()
        data.spOrder            = $("#spOrder").val()
        data.spFamily           = $("#spFamily").val()
        data.spSubFamily        = $("#spSubFamily").val()
        data.spGenus            = $("#spGenus").val()
        data.spGenusAuth        = $("#spGenusAuth").val()
        data.spSpecies          = $("#spSpecies").val()
        data.spSpeciesAuth      = $("#spSpeciesAuth").val()
        data.spSubSpecies       = $("#spSubSpecies").val()
        data.spSubSpeciesAuth   = $("#spSubSpeciesAuth").val()
        data.spScName           = $("#spScName").val()
        data.spScNameAuth       = $("#spScNameAuth").val()
        data.spEngName          = $("#spEngName").val()
        data.spLocalName        = $("#spLocalName").val()
        data.spHabitat          = $("#spHabitat").val()
        data.spBdDist           = $("#spBdDist").val()
        data.spGbDist           = $("#spGbDist").val()
        data.spMap              = $("#spMap").val()
        data.spIucnBd           = $('#spIucnBd').summernote('isEmpty') ? '' : $('#spIucnBd').summernote('code')
        data.spIucnBdYear       = $('#spIucnBdYear').val()
        data.spIucnGb           = $('#spIucnGb').summernote('isEmpty') ? '' : $('#spIucnGb').summernote('code')
        data.spIucnGbYear       = $('#spIucnGbYear').val()
        data.spCitis            = $('#spCitis').val()
        data.spShortDes         = $('#spShortDes').summernote('isEmpty') ? '' : $('#spShortDes').summernote('code')
        data.spBiology          = $('#spBiology').summernote('isEmpty') ? '' : $('#spBiology').summernote('code')
        data.spCitePage         = $('#spCitePage').summernote('isEmpty') ? '' : $('#spCitePage').summernote('code')

        $('#sequences-area').find('.refItem > .summerNoteLink').each(function(i){
            if(!$(this).summernote('isEmpty')){
                i++
                data['Sequence'+i] = $(this).summernote('code')
            }
        })
        
        $('#occurances-area').find('.refItem > .summerNoteLink').each(function(i){
            if(!$(this).summernote('isEmpty')){
                i++
                data['Occurrence'+i] = $(this).summernote('code')
            }
        })

        $('#acceptedNames-area').find('.refItem > .summerNoteLink').each(function(i){
            if(!$(this).summernote('isEmpty')){
                i++
                data['AcceptedNameSynonym'+i] = $(this).summernote('code')
            }
        })

        $('#references-area').find('.refItem > .summerNoteLink').each(function(i){
            if(!$(this).summernote('isEmpty')){
                i++
                data['Reference'+i] = $(this).summernote('code')
            }
        })
        
        let photosArr = [];
        $("#featureImages span img").each(function(e){
            photosArr.push($(this).attr("dataId"))
        });
        
        data.spPhotos = photosArr.join(',')
        
        update(id, data)
    });


    function update(id, data){
        $.ajax({
            url: `../back-end/api/species/update.php?id=${id}`,
            method: "PUT",
            data: JSON.stringify(data),
            beforeSend: function () {
                $(".preloader").show();
            },
            complete: function () {
                $(".preloader").hide();                
            },
            success: function (res) {
                if(res.status == '200'){
                    alert('Success! '+res.msg)
                }else{
                    alert('Sorry! '+res.msg)
                }
            },
            error: function (error) {
                console.log(error.responseText);
            }
        });
    }

   

})