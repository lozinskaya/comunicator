//
//  DetailVC.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 11.01.2021.
//

import UIKit

class DetailVC: UIViewController {
    
    var chooseCell: Array<String>?
    var chooseCountPersons: String!
    @IBOutlet weak var labelCell: UILabel!
    @IBOutlet weak var imgCell: UIImageView!
    @IBOutlet weak var timeCell: UILabel!
    @IBOutlet weak var personCell: UILabel!
    @IBOutlet weak var descriptionCell: UILabel!
    //кнопка записи
    @IBOutlet weak var btnRegisterOnEvent: UIButton!
    //Нажатие кнопки записаться
    @IBAction func registerOnEvent(_ sender: Any) {
        let vc = registerOnEventViewController()
            vc.modalPresentationStyle = .custom
            present(vc, animated: true, completion: nil)
    }
    //Количество человек зарегавшихся на меро с пользователем
    @IBOutlet weak var afterRegistrCountPersons: UILabel!

    //Кнопка отмены записи
    @IBOutlet weak var btnCancelReg: UIButton!
    //Нажатие кнопки отмены регистрации на мероприятие
    @IBAction func CancelRegistration(_ sender: Any) {
        let alert = UIAlertController(title: "Отмена записи", message: "Вы сможете записаться снова", preferredStyle: UIAlertController.Style.alert)
        alert.addAction(UIAlertAction(title: "Не отменять", style: UIAlertAction.Style.default, handler: nil))
        let cancelAlert = UIAlertAction(title: "Отменить", style: UIAlertAction.Style.default, handler: { (action: UIAlertAction!) in
            self.afterRegistrCountPersons.isHidden = true
            self.btnRegisterOnEvent.isHidden = false
            self.btnCancelReg.isHidden = true
            })
        cancelAlert.setValue(UIColor.red, forKey: "titleTextColor")
        alert.addAction(cancelAlert)
        self.present(alert, animated: true, completion: nil)
    }
    override func viewDidLoad() {
        super.viewDidLoad()
        NotificationCenter.default.addObserver(self, selector: #selector(ifResultSuccess), name: NSNotification.Name(rawValue: "ResultSuccess"), object: nil)

        labelCell.text = chooseCell?[1]
        imgCell.image = UIImage(named: "eventImg")
        descriptionCell.text = chooseCell?[2]
        timeCell.text = chooseCell?[3]
        personCell.text = chooseCell?[4]
        print("----")
        print(chooseCountPersons)
    }
    
    @objc func ifResultSuccess() {
           print("Received Notification")
        afterRegistrCountPersons.isHidden = false
        btnRegisterOnEvent.isHidden = true
        btnCancelReg.isHidden = false
        afterRegistrCountPersons.text = "Вы и " + String(1) + " ваш друг записаны"
    }
    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}
