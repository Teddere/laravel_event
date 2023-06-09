import Swal from "sweetalert2";
export function useSuccessAlert(message){

    Swal.fire({
        toast:true,
        icon:'success',
        title:message,
        animation:false,
        position:'bottom-end',
        showConfirmButton:false,
        timer:5000,
    });
}
export function useErrorAlert(message){
    Swal.fire({
        toast:true,
        icon:'error',
        title:message,
        animation:false,
        position:'bottom-end',
        showConfirmButton:false,
        timer:5000,
    });
}

export function useConfirm(id,message,title,callback){
    Swal.fire({
        title:title,
        icon:'error',
        text:message,
        showCancelButton:true,
        confirmButtonText:'Valider',
        cancelButtonText:'Annuler',
        confirmButtonColor:'#2563eb',
        cancelButtonColor:'#dc2626',
        background: 'rgb(248 250 252)',
    }).then((result)=>{
        if(result.isConfirmed){
            callback(id);
        }
    }).catch((error)=>{
        console.Log(error);
    })
}
