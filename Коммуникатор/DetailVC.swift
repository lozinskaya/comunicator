//
//  DetailVC.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 11.01.2021.
//

import UIKit

class DetailVC: UIViewController {

    var chooseCell: Array<String>?
    @IBOutlet weak var labelCell: UILabel!
    @IBOutlet weak var imgCell: UIImageView!
    @IBOutlet weak var timeCell: UILabel!
    @IBOutlet weak var personCell: UILabel!
    @IBOutlet weak var descriptionCell: UILabel!
    @IBAction func registerOnEvent(_ sender: Any) {
        let vc = registerOnEventViewController()
            vc.modalPresentationStyle = .custom
            present(vc, animated: true, completion: nil)
    }
    //Количество человек зарегавшихся на меро с пользователем
    @IBOutlet weak var afterRegistrCountPersons: UILabel!
    //Кнопка отмены регистрации на мероприятие
    @IBAction func CancelRegistration(_ sender: Any) {
        let alert = UIAlertController(title: "Отмена записи", message: "Вы сможете записаться снова", preferredStyle: UIAlertController.Style.alert)
        alert.addAction(UIAlertAction(title: "Не отменять", style: UIAlertAction.Style.default, handler: nil))
        let cancelAlert = UIAlertAction(title: "Отменить", style: UIAlertAction.Style.default, handler: nil)
        cancelAlert.setValue(UIColor.red, forKey: "titleTextColor")
        alert.addAction(cancelAlert)
        self.present(alert, animated: true, completion: nil)
    }
    override func viewDidLoad() {
        super.viewDidLoad()

        labelCell.text = chooseCell?[1]
        imgCell.image = UIImage(named: "eventImg")
        descriptionCell.text = chooseCell?[2]
        timeCell.text = chooseCell?[3]
        personCell.text = chooseCell?[4]
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
