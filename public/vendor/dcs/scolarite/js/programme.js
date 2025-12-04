function getNiveauSpecialites(id) {
    getTheContent('scolarites/programmes/getNiveauSpecialites/' + id, '#specialitesCnt');
}

function getNiveauxPedagogiquesByAnnee(val,programme_id,parent = 'addModuleForm') {
    getTheContent('scolarites/programmes/modules/niveauByAnnee/'+programme_id+'/'+val+"/"+parent,'.niveaux_ped_cnt');
}
