<?php


namespace App\Service;


use App\Repository\ProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;

class UserService
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;
    /**
     * @var ProfilRepository
     */
    private ProfilRepository $profilRepository;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;


    /**
     * InscriptionService constructor.
     */
    public function __construct( UserPasswordEncoderInterface $encoder,SerializerInterface $serializer, ProfilRepository $profilRepository,ValidatorInterface $validator)
    {
        $this->encoder =$encoder;
        $this->serializer = $serializer;
        $this->profilRepository = $profilRepository;
        $this->validator = $validator;
    }

    /**
     * put image of user
     * @param Request $request
     * @param string|null $fileName
     * @return array
     */
    public function getAttributes(Request $request,string $fileName = null){
        $raw = $request->getContent();
        //dd($raw);
        //dd($request->headers->get("content-type"));
        $delimiteur = "multipart/form-data; boundary=";
        $boundary= "--" . explode($delimiteur,$request->headers->get("content-type"))[1];
        $elements = str_replace([$boundary,'Content-Disposition: form-data;',"name="],"",$raw);
        //dd($elements);
        $elementsTab = explode("\r\n\r\n",$elements);
        //dd($elementsTab);
        $data =[];
        for ($i=0;isset($elementsTab[$i+1]);$i+=2){
            //dd($elementsTab[$i+1]);
            $key = str_replace(["\r\n",' "','"'],'',$elementsTab[$i]);
            if (strchr($key,$fileName)){
                $stream =fopen('php://memory','r+');
                fwrite($stream,$elementsTab[$i +1]);
                rewind($stream);
                $data[$fileName] = $stream;
            }else{
                $val = str_replace(["\r\n", "--"],'',$elementsTab[$i+1]);
                $data[$key] = $val;
            }
        }
        return $data;

    }


    public function Validate($utilisateur)
    {
        $errorString ='';
        $error = $this->validator->validate($utilisateur);
        if(isset($error) && $error >0){ $errorString = $this->serializer->serialize($error,'json');}
        return $errorString;
    }


}